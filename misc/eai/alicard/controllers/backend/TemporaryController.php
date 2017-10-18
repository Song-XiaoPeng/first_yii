<?php

namespace apps\alicard\controllers\backend;

use apps\alicard\ar\AlicardRecord;
use portal\models\UserModel;
use Yii;
use apps\BackendController;

class TemporaryController extends BackendController
{

    private $ls_prefix = 'linshika_';

    public function highLightRules()
    {
        return 'list';
    }

    //卡列表
    public function actionIndex()
    {

        return $this->render('index');
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
