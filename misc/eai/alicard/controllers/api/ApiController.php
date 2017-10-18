<?php

namespace apps\alicard\controllers\api;

use portal\controllers\Controller;
use portal\models\UserModel;
use Yii;

class ApiController extends Controller
{
    //返回组织架构信息接口
    public function actionOrganize()
    {
        $organize = UserModel::roles('department');
        Yii::$app->message->success($organize);
    }
}