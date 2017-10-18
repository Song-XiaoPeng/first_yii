<?php

namespace apps\alicard\controllers\backend;

use apps\alicard\ar\AlicardRecord;
use portal\models\UserModel;
use Yii;
use apps\BackendController;

class ListController extends BackendController
{

    private $ls_prefix = 'linshika_';

    public function highLightRules()
    {
        return 'list';
    }

    public function actionIndex()
    {
        $type = intval(Yii::$app->request->get("type", 0));
        $pages = Yii::$app->page->getLimit();
        $card = AlicardRecord::find()->where(['type' => $type])->orderBy('id desc');
        Yii::$app->page->setTotal($card->count());
        $lists = $card->offset($pages[0])->limit($pages[1])->asArray()->all();
        if ($type == 0 && !empty($lists)) {
            foreach ($lists as &$v) {
                //获取用户信息
                $info = UserModel::getInfo($v['uid']);
                $v['number'] = $info['number'];
                $v['depart_name'] = $info['depart_name'];
                $v['realname'] = $info['realname'];
            }
        }
        return $this->render('index', ['lists' => $lists]);
    }

    public function actionAdd()
    {
        $id = intval(Yii::$app->request->get("id"));
        $info = AlicardRecord::find()->where(['id' => $id])->asArray()->one();
        if (!empty($info)) {
            $info['cardid'] = ltrim($info['cardid'], $this->ls_prefix);
            unset($info['password']);
        }
        return $this->render("add", ['info' => $info, 'ls_prefix' => $this->ls_prefix]);
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
