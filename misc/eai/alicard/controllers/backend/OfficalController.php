<?php

namespace apps\alicard\controllers\backend;

use apps\alicard\ar\AlicardRecord;
use apps\alicard\ar\AlicardType;
use apps\alicard\ar\AlicardTypeOrganize;
use apps\alicard\models\AlicardTypeModel;
use portal\models\UserModel;
use Yii;
use apps\BackendController;

class OfficalController extends BackendController
{

    private $ls_prefix = 'linshika_';

    public function highLightRules()
    {
        return 'list';
    }

    //卡类别
    public function actionCategory()
    {
        //获得卡类别
        $type = AlicardType::find()->orderBy('id desc')->asArray()->all();
        $type = AlicardTypeModel::typeList($type);
        return $this->render('category', [
            'data' => $type
        ]);
    }

    //添加/修改卡类别
    public function actionSaveType()
    {
        $id = intval(Yii::$app->request->post("id"));
        $name = Yii::$app->request->post("name");
        $organize_ids = Yii::$app->request->post("organize_id");

        if (empty($name) || empty($organize_ids)) {
            Yii::$app->message->fail("必填参数为空！");
        }

        $exists = AlicardType::findOne(['name' => $name]);
        if ($exists) {
            $id != $exists->id && Yii::$app->message->fail("卡类型已经存在！");
        }

        try {
            //保存卡类型返回主键id
            $ar = $exists ?: new AlicardType();
            $ar->attributes = [
                'name' => $name
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
                $ar_type_organize->attributes = [
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
            $ar->delete();
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
        $pages = Yii::$app->page->getLimit();
        $card = AlicardRecord::find()->where(['and', ['!=', 'type', 0], ['!=', 'uid', 0]])->orderBy('id desc');
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
        return $this->render('index', ['lists' => $lists]);
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
            $ar->delete();
            Yii::$app->message->success();
        } catch (\Exception $e) {
            Yii::$app->message->fail($e->getMessage());
        }
    }

    //卡设置
    public function actionSetting()
    {

        return $this->render('setting');
    }

    public function actionAdd()
    {

        return $this->render("add");
    }


    public function actionSave()
    {
        $id = intval(Yii::$app->request->post("id"));
        $cardid = Yii::$app->request->post("cardid");
        $pwd = Yii::$app->request->post("password");
        $validate = Yii::$app->request->post("valid_date", 0);

        if (empty($cardid) || empty($pwd) || empty($validate)) {
            Yii::$app->message->fail("必填参数为空！");
        }

        $cardid = $this->ls_prefix . $cardid;
        $exists = AlicardRecord::findOne(['cardid' => $cardid]);
//        echo '<pre>';var_dump($exists);die;
        if ($exists) {
            $id != $exists->id && Yii::$app->message->fail("卡号已经存在！");
            empty($exists->type) && Yii::$app->message->fail("卡号已经存在且不是临时卡！");
        }

        $ar = $exists ?: new AlicardRecord();
        $ar->attributes = [
            'password' => Yii::$app->security->generatePasswordHash($pwd, 7),
            'cardid' => $cardid,
            'valid_date' => strtotime($validate),
            'type' => 1,
        ];
        try {
            $ar->save();
            Yii::$app->message->success();
        } catch (\Exception $e) {
            Yii::$app->message->fail($e->getMessage());
        }
    }

}
