<?php
namespace backend\components;

use yii\base\ActionFilter;

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/8/2
 * Time: 16:06
 */
class MyBehaviour extends ActionFilter
{
    public function beforeAction($action)
    {
        var_dump('yii wcnmlgb');
        return false;
    }

    /**
     * 判断当前用户是否是访客
     * @return bool
     */
    public function isGuest()
    {
        return \Yii::$app->user->isGuest;
    }


}