<?php

namespace apps\alicard\controllers\backend;

use apps\alicard\ar\AlicardRecord;
use portal\models\UserModel;
use Yii;
use apps\BackendController;

class AddressController extends BackendController
{

    private $ls_prefix = 'linshika_';

    public function highLightRules()
    {
        return 'list';
    }

    //领卡地址
    public function actionIndex()
    {

        return $this->render('index');
    }
}
