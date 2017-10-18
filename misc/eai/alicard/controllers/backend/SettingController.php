<?php

namespace apps\alicard\controllers\backend;

use apps\alicard\ar\AlicardTemplate;
use portal\models\ManagerModel;
use Yii;
use portal\models\LogModel;
use apps\BackendController;
use portal\helpers\ErrorMessage;

class SettingController extends BackendController {

    public function highLightRules() {
        return 'setting';
    }

    public function actionIndex() {
        $setting = AlicardTemplate::find()->asArray()->one();
        $tips = Yii::$app->config->item("field.tips");
        $columns = Yii::$app->config->item("field.columns");
        $columnList = $codeList = [];
        $info = [];
        if(!empty($setting) && !empty($info = json_decode($setting['ext'], true))) {
            $columnList = $info['list_columns'];
            $codeList = $info['action_list'];
            unset($info['list_columns'], $info['action_list']);
        }
        $render = [
            'tips' => $tips,
            'columns' => $columns,
            'info' => $info,
            'columnList' => $columnList,
            'codeList' => $codeList,
        ];
        return $this->render('index', $render);
    }

    public function actionSave() {
        $data = Yii::$app->request->post('info');
        $data['list_columns'] = Yii::$app->request->post('columnList');
        $data['action_list'] = Yii::$app->request->post('codeList');
        $data['columns'] = Yii::$app->request->post('columns',[]);

        $data['prefix'] = \Misc::get('common.env');
        $obj = AlicardTemplate::find()->one();
        if (empty($obj)) {
            $ali_data = $this->template($data, true);
            $rs = Yii::$app->aop->call('alipay.marketing.card.template.create', $ali_data);
            $response = $rs->alipay_marketing_card_template_create_response;
            if ($response->code === "10000") {
                $template_id = $response->template_id;
            } else {
                Yii::$app->message->fail('请求支付宝接口失败 '.$response->msg . ' ' . $response->sub_msg);
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
                Yii::$app->message->fail('请求支付宝接口失败 '.$response->msg . ' ' . $response->sub_msg);
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
        ];
        $obj->save();
        if (empty($obj->id)) {
            Yii::$app->message->error(ErrorMessage::ERR_FAIL);
        }
        LogModel::save(['app' => $this->module->appkey, 'content' => '设置校园卡']);
        Yii::$app->message->success();
    }

    protected function template($data, $create = true) {
        $res = [
            'request_id' => strval(time()),
            'biz_no_prefix' => $data['prefix'],
            'write_off_type' => 'dqrcode', //qrcode、dqrcode
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

}
