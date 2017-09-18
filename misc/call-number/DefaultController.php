<?php

namespace apps\pc\controllers;

use core\helpers\CacheKey;
use core\models\ManagerModel;
use core\models\UserModel;
use Yii;
use core\controllers\PcController;
use core\services\Manager;

use core\services\User;

use core\services\Queue;


class DefaultController extends PcController
{
    public function actionIndex()
    {

        return $this->render('index');
    }

    //修改个人信息
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            $data = [];
            $uid = Yii::$app->manager->uid;
            $tel = Yii::$app->request->post('tel');
            $address = Yii::$app->request->post('address');
            $login_pass = Yii::$app->request->post('password');
            $new_pass = Yii::$app->request->post('new_password');
            $re_pass = Yii::$app->request->post('re_password');
            $phone = Yii::$app->request->post('phone');
            $verify_code = Yii::$app->request->post('verify_code');

            //验证密码
            if ($login_pass) {
                if (!($login_pass && $new_pass && $re_pass)) Yii::$app->message->fail('密码为空');
                if (!\verifypasswd($new_pass, 6, 20)) Yii::$app->message->fail('请输入6-20位字母,数字组合的密码');
                $manager = ManagerModel::one(['id' => $uid]);
                if (!Yii::$app->security->validatePassword($login_pass, $manager['password'])) Yii::$app->message->fail('原始密码输入错误');
                if ($new_pass != $re_pass) Yii::$app->message->fail('两次输入的密码不一致');
                $data['password'] = Yii::$app->security->generatePasswordHash($new_pass);
            }

            //验证手机
            if ($phone) {
                if (!($phone && $verify_code)) Yii::$app->message->fail('手机或验证码为空');
                if (!isMobile($phone)) Yii::$app->message->fail('请输入11位长度的有效手机号');
                if (ManagerModel::one(['phone' => $phone])) Yii::$app->message->fail('此手机号码已注册，请输入新的手机号');
                $key = CacheKey::smscode($phone);
                if ($verify_code != Yii::$app->redis->get($key)) Yii::$app->message->fail('手机验证码输入错误或者已过期，请重试');
                $data['phone'] = $phone;
            }

            if (!preg_match("/^[\d,-]{0,15}$/", $tel)) Yii::$app->message->fail('请输入小于15位的数字和"-"组成的电话号码');
            $data['tel'] = $tel;

            if (iconv_strlen($address) > 29) Yii::$app->message->fail('地址长度超过最大限制');
            $data['address'] = $address;

            $rs = ManagerModel::update($data, ['id' => $uid]);
            if ($rs) {
                Yii::$app->message->success('个人信息修改成功');
            } else {
                $data['id'] = $uid;
                if (ManagerModel::one($data))
                    Yii::$app->message->success('个人信息修改成功');
                else
                    Yii::$app->message->fail('个人信息修改失败');
            }
        }
    }

    //验收手机验证码
    public function actionVerifyCode()
    {
        $phone = Yii::$app->request->post('phone');
        try {
            if (User::sendVerifyCode($phone)) {
                Yii::$app->message->success();
            } else {
                Yii::$app->message->fail('发送失败');
            }
        } catch (\Exception $e) {
            Yii::$app->message->fail('发送失败');
        }
    }

    public function actionCallNumber(){

    	$manager_id = Yii::$app->manager->info['id'];
    	$rs = Queue::callNumber($manager_id);
    }

    public function actionFinish(){


    }

    public function actionPass(){

    }
}
