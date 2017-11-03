<?php

namespace apps\alicard\controllers\backend;

use app\models\ImportExportModel;
use apps\alicard\ar\AlicardRecord;
use apps\alicard\ar\AlicardTemplate;
use apps\alicard\ar\AlicardType;
use apps\alicard\ar\AlicardTypeOrganize;
use apps\alicard\models\AlicardTypeModel;
use lib\components\Excel;
use portal\models\LogModel;
use portal\models\UserModel;
use UcSdk\user\User;
use Yii;
use apps\BackendController;
use portal\helpers\ErrorMessage;


class OfficalController extends BackendController
{

    private $ls_prefix = 'linshika_';

    public function highLightRules()
    {
        return [
            [['*'], 'offical'],
        ];
    }

    public function actionTest()
    {
        return $this->render('test');
    }

    //卡类别
    public function actionCategory()
    {
        //获得卡类别
        $type = AlicardType::find()->where(['is_del' => 0])->orderBy('sort asc')->asArray()->all();
        $type = AlicardTypeModel::typeList($type);

        /*echo '<pre>';
        var_dump($type);die;*/
        return $this->render('category', [
            'data' => $type
        ]);
    }

    //添加/修改卡类别
    public function actionSaveType()
    {
        $id = intval(Yii::$app->request->post("id"));
        $name = Yii::$app->request->post("name");
        $validate = intval(Yii::$app->request->post("valid_date", 0));
        $organize_ids = Yii::$app->request->post("org");
        $sort = intval(Yii::$app->request->post("sort", 0));
        $type = intval(Yii::$app->request->post("type", 0));

        if (empty($name) || empty($organize_ids) || empty($validate)) {
            Yii::$app->message->fail("必填参数为空！");
        }

        $exists = AlicardType::findOne(['name' => $name, 'is_del' => 0]);
        if ($exists) {
            $id != $exists->id && Yii::$app->message->fail("卡类型已经存在！");
        }

/*        if ($id && !$exists) {
            $card = AlicardRecord::find()->where(['type' => $id])->asArray()->all();
            if ($card) {
                $setting = AlicardTemplate::find()->where(['type' => 0])->asArray()->one();
                if ($setting) {
                    $queueData = [
                        'method' => 'apps.alicard.models.AlicardTypeModel.updateAlicard',
                        'data' => ['card' => $card, 'name' => $name, 'setting' => $setting]
                    ];
                    Yii::$app->queue->push($queueData);
                }
            }
        }*/

        try {
            $ar = AlicardType::findOne(['id' => $id]);
            //保存卡类型返回主键id
            $ar = $ar ?: new AlicardType();
            $ar->attributes = [
                'name' => $name,
                'sort' => $sort,
                'type' => $type,
                'valid_date' => $validate
            ];
            $ar->save();
            $type_id = $ar->getPrimaryKey();

            //卡类型和组织关联
            $ar_type_organize = new AlicardTypeOrganize();
            if ($id == $type_id) {
                //删除原先关联的组织架构
                $ar_type_organize->deleteAll(['type_id' => $id]);
            }
            //保存卡类型与组织架构
            foreach ($organize_ids as $v) {
                $_model = clone $ar_type_organize;
                $_model->attributes = [
                    'type_id' => $type_id,
                    'organize_id' => $v
                ];
                $_model->save();
            }

            Yii::$app->message->success();
        } catch (\Exception $e) {
            Yii::$app->message->fail($e->getMessage());
        }
    }

    //删除卡类别
    public function actionDeleteType()
    {
        $id = intval(Yii::$app->request->post("id"));
        $ar = AlicardType::findOne(['id' => $id]);
        if (empty($ar)) {
            Yii::$app->message->fail("必填参数错误！");
        }

        $trans = Yii::$app->db->beginTransaction();
        try {
            //删除卡类别
            $ar->is_del = 1;
            $ar->save();

            $del_card = AlicardRecord::find()->where([''])->asArray()->all();

            if ($del_card) {
                $del_card_no = array_column($del_card, 'biz_card_no');
                //写入redis队列
                if ($del_card_no) {
                    $queueData = [
                        'method' => 'apps.alicard.models.AlicardTypeModel.delAlicard',
                        'data' => $del_card_no
                    ];
                    Yii::$app->queue->push($queueData);
                }
            }

            //此前领取的此卡类别的校园卡将全部删除
            AlicardRecord::deleteAll(['type' => $id]);

            $trans->commit();
            Yii::$app->message->success();
        } catch (\Exception $e) {
            $trans->rollback();
            Yii::$app->message->fail($e->getMessage());
        }
    }

    //卡列表
    public function actionIndex()
    {
        //搜索条件
        $get = Yii::$app->request->get();
        $where[] = ['is_del' => 0];
        !empty($get['cardid']) && $where[] = ['cardid' => $get['cardid']];
        $where[] = !empty($get['type']) ? ['type' => $get['type']] : ['!=', 'type', 0];

        if (isset($get['name']) && $get["name"] !== "") {
            //根据name找到uid
            $user_lists = UserModel::listsNoPage(['keyword' => $get['name']]);
            $uids = array_column($user_lists, 'uid');
            $where[] = ['uid' => $uids];
        }

        if (!empty($get['start']) && !empty($get['end'])) {
            $where[] = [
                'and',
                ['>=', 'valid_date', strtotime($get['start'])],
                ['<=', 'valid_date', strtotime($get['end']) + 24 * 3600]
            ];
        }

        if (!empty($get['created'])) {
            $where[] = [
                'and',
                ['>=', 'created', strtotime($get['created'])],
                ['<=', 'created', strtotime($get['created']) + 24 * 3600]
            ];
        }

        if (count($where) > 1) {
            array_unshift($where, 'AND');
        } else {
            $where = array_shift($where);
        }

        $pages = Yii::$app->page->getLimit();
        $card = AlicardRecord::find()->where($where)->orderBy('valid_date desc,id asc');
        Yii::$app->page->setTotal($card->count());
        $lists = $card->offset($pages[0])->limit($pages[1])->asArray()->all();

        if (!empty($lists)) {
            $type_id = array_column($lists, 'type');
            $type_lists = AlicardType::find()->where(['id' => $type_id])->asArray()->all();
            $type_lists = array_column($type_lists, null, 'id');
            foreach ($lists as &$v) {
                //获取用户信息
                $info = UserModel::getInfo($v['uid']);
                $v['number'] = $info['number'];
                $v['depart_name'] = $info['depart_name'];
                $v['realname'] = $info['realname'];
                $v['type_name'] = $type_lists[$v['type']]['name'];
            }
        }
        //卡类型
        $type = AlicardType::find()->where(['is_del' => 0])->orderBy('id desc')->asArray()->all();
        return $this->render('index', ['lists' => $lists, 'type' => $type]);
    }

    //删除卡
    public function actionDelete()
    {
        $id = intval(Yii::$app->request->post("id"));
        $ar = AlicardRecord::findOne(['id' => $id]);
        if (empty($ar)) {
            Yii::$app->message->fail("必填参数错误！");
        }
        try {
            $rs = Yii::$app->aop->call('alipay.marketing.card.delete', [
                'out_serial_no' => strval(time()),
                'target_card_no' => $ar['biz_card_no'],
                'target_card_no_type' => 'BIZ_CARD',
                'reason_code' => 'USER_UNBUND'
            ]);
            $response = $rs->alipay_marketing_card_delete_response;
            if ($response->code !== '10000') {
                throw new \Exception($response->msg);
            }
            $ar->is_del = 1;
            $ar->save();
            Yii::$app->message->success();
        } catch (\Exception $e) {
            Yii::$app->message->fail($e->getMessage());
        }
    }

    //卡设置
    public function actionSetting()
    {
        $setting = AlicardTemplate::find()->where(['type' => 0])->asArray()->one();
        $tips = Yii::$app->config->item("field.tips");
        $columns = Yii::$app->config->item("field.columns");
        $columnList = $codeList = [];
        $info = [];
        if (!empty($setting) && !empty($info = json_decode($setting['ext'], true))) {
            $columnList = $info['list_columns'];
            $codeList = $info['action_list'];
            unset($info['list_columns'], $info['action_list']);
        }
        $info['id'] = $setting['id'];
        $render = [
            'tips' => $tips,
            'columns' => $columns,
            'info' => $info,
            'columnList' => $columnList,
            'codeList' => $codeList,
        ];

        return $this->render('setting', $render);
    }

    //保存卡设置
    public function actionSave()
    {
        $data = Yii::$app->request->post('info');
        $data['list_columns'] = Yii::$app->request->post('columnList');
        $data['action_list'] = Yii::$app->request->post('codeList');
        $data['columns'] = Yii::$app->request->post('columns', []);
        $data['prefix'] = \Misc::get('common.env');
        $obj = AlicardTemplate::find()->where(['id' => $data['id']])->one();

        if (empty($obj)) {
            $ali_data = $this->template($data, true);
            $rs = Yii::$app->aop->call('alipay.marketing.card.template.create', $ali_data);
            $response = $rs->alipay_marketing_card_template_create_response;
            if ($response->code === "10000") {
                $template_id = $response->template_id;
            } else {
                Yii::$app->message->fail('请求支付宝接口失败 ' . $response->msg . ' ' . $response->sub_msg);
            }
            $obj = new AlicardTemplate();
            $operate = '添加';
        } else {
            $data['template_id'] = $obj->template_id;
            $ali_data = $this->template($data, false);

            $rs = Yii::$app->aop->call('alipay.marketing.card.template.modify', $ali_data);
            $response = $rs->alipay_marketing_card_template_modify_response;
            if ($response->code === "10000") {
                $template_id = $response->template_id;
            } else {
                Yii::$app->message->fail('请求支付宝接口失败 ' . $response->msg . ' ' . $response->sub_msg);
            }
            $operate = '修改';
        }
        $obj->attributes = [
            'template_style_info' => json_encode($ali_data['template_style_info']),
            'write_off_type' => $ali_data['write_off_type'],
            'column_info_list' => json_encode($ali_data['column_info_list']),
            'field_rule_list' => json_encode($ali_data['field_rule_list']),
            'card_action_list' => json_encode($ali_data['card_action_list']),
            'ext' => json_encode($data),
            'template_id' => $template_id,
            'creator' => Yii::$app->manager->uid,
            'modified' => time(),
            'created' => time(),
            'type' => 0
        ];

        $obj->save();
        if (empty($obj->id)) {
            Yii::$app->message->error(ErrorMessage::ERR_FAIL);
        }
        LogModel::save(['app' => $this->module->appkey, 'content' => '设置校园卡']);
        Yii::$app->message->success();
    }

    protected function template($data, $create = true)
    {
        $res = [
            'request_id' => strval(time()),
            'biz_no_prefix' => $data['prefix'],
            'write_off_type' => 'dqrcode', //qrcode、dqrcode
//            'notify_url' => 'http://ucenter.datamorality.com/pay/api/alipay',
            'template_style_info' => [
                'card_show_name' => $data['title'],
                'logo_id' => $data['logo_id'],
                'background_id' => $data['background_id'],
                'bg_color' => 'rgb(55,112,179)',
                'front_text_list_enable' => true, //是否在卡面展示文案信息
                'front_image_enable' => true, //是否在卡面展示（个人头像）图片信息
            ],
            'field_rule_list' => [
                [
                    'field_name' => 'OpenDate',
                    'rule_name' => 'DATE_IN_FUTURE',
                    'rule_value' => '0d'
                ]
            ],
        ];

        if ($create) {
            $res['biz_no_suffix_len'] = 10;
            $res['card_type'] = 'OUT_MEMBER_CARD';
        } else {
            $res['template_id'] = $data['template_id'];
        }

        $res['card_action_list'] = [];
        if (!empty($data['action_list'])) {
            foreach ($data['action_list'] as $k => $v) {
                $res['card_action_list'][] = [
                    'code' => $v['code'],
                    'text' => $v['title'],
                    'url' => $v['url']
                ];
            }
        }

        $res['column_info_list'] = [];
        if (!empty($data['list_columns'])) {
            foreach ($data['list_columns'] as $k => $v) {
                $res['column_info_list'][] = [
                    'code' => $v['code'],
                    'operate_type' => 'openWeb',
                    'title' => $v['title'],
                    'more_info' => [
                        'title' => $v['title'],
                        'url' => $v['url'],
                    ]
                ];
            }
        }
        return $res;
    }

    //导出
    public function actionExport()
    {
        $lists = AlicardRecord::find()->where(['!=', 'type', 0])->asArray()->all();

        $data = [];
        if (!empty($lists)) {
            $type_id = array_column($lists, 'type');
            $type_lists = AlicardType::find()->where(['id' => $type_id])->asArray()->all();
            $type_lists = array_column($type_lists, null, 'id');
            foreach ($lists as $k => $v) {
                //获取用户信息
                $info = UserModel::getInfo($v['uid']);
                $v['number'] = $info['number'];
                $v['depart_name'] = $info['depart_name'];
                $v['realname'] = $info['realname'];
                $v['type_name'] = $type_lists[$v['type']]['name'];
                $data[$k]['number'] = $v['cardid'];
                $data[$k]['name'] = $v['realname'];
                $data[$k]['depart'] = $v['depart_name'];
                $data[$k]['type'] = $v['type_name'];
                $data[$k]['valid_date'] = date('Y-m-d H:i:s', $v['valid_date']);
                $data[$k]['created'] = date('Y-m-d H:i:s', $v['created']);
            }
        }

        $sheet['name'] = 'sheet1';
        $sheet['data'] = $data;
        $sheet['index'] = ['卡号' => 'number', "姓名" => 'name', '部门' => 'depart', '类别' => 'type', '有效期' => 'valid_date', '领取时间' => 'created'];
        $filename = '支付宝校园卡正式卡列表-' . date('Ymd', time());
        Excel::export($filename, $sheet);
    }
}
