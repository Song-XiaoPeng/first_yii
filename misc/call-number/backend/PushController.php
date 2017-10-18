<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/08/22
 * Time: 17:57
 */

namespace apps\backend\controllers;

use core\helpers\CacheKey;
use core\models\ManagerModel;
use core\models\PushMsgModel;
use core\services\Manager;
use core\services\platform;
use core\models\FeedbackModel;
use core\services\PushMsg;
use Yii;
use core\controllers\BackendController;
use core\models\NoticeModel;
use core\models\UserModel;

class PushController extends BackendController
{
    public function highLightRules()
    {
        return [
            ['actions' => ['push', 'push-msg-save'], 'rule' => 'push.push'],
            ['actions' => ['push'], 'rule' => 'push'],
        ];
    }

    public function accessRules()
    {
        return [
            ['allow', 'actions' => ['push', 'push-msg-save', 'qrcode-size-save', 'qrcode-msg-save', 'reset'], 'rules' => ['push']],
        ];
    }

    ###=====================================================================================
    ### 推送管理
    ###=====================================================================================
    public function actionPush()
    {
        $key = CacheKey::pushMsg();
        $push_msg = Yii::$app->dbcache->get($key);
        $key = CacheKey::pushQrcodeSize();
        $size = Yii::$app->dbcache->get($key);

        $key = CacheKey::pushQrcodeMsg();
        $qrcode_msg = Yii::$app->dbcache->get($key);
        empty($qrcode_msg) && $qrcode_msg = [
            [
                'type' => 1,
                'text' => '北航师生服务大厅欢迎您！',
            ],
            [
                'type' => 1,
                'text' => '···············',
            ],
            [
                'type' => 1,
                'text' => '预约号:{queue_id}',
            ],
            [
                'type' => 1,
                'text' => '等待人数:{wait}',
            ],
            [
                'type' => 1,
                'text' => '预约业务:{business}',
            ],
            [
                'type' => 1,
                'text' => '···············',
            ],
            [
                'type' => 1,
                'text' => '欢迎扫描二维码进行评价！',
            ]
        ];
        if (!$size) {
            $size['height'] = 200;
            $size['width'] = 200;
        }
        return $this->render('push', [
            'data' => $push_msg,
            'size' => $size,
            'qrcode_msg' => $qrcode_msg
        ]);
    }

    public function actionPushMsgSave()
    {
        $data['template'] = Yii::$app->request->post('template');
        $data['queue_order_id'] = trim(Yii::$app->request->post('id'));

        $reg = "/^\d*(\,\d*)*$/";
        if (!preg_match($reg, $data['queue_order_id'])) Yii::$app->message->fail('请填写正确的数据格式');

        $key = CacheKey::pushMsg();
        $rs = Yii::$app->dbcache->set($key, $data);

        if ($rs) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail();
        }
    }

    //二维码模版设置
    public function actionQrcodeMsgSave()
    {
        $message = Yii::$app->request->post('message');
        $width = Yii::$app->request->post('width');
        $height = Yii::$app->request->post('height');
        $type = Yii::$app->request->post('type');
        $url = Yii::$app->request->post('text');

        $i = 0;
        $tmp = [];
        foreach ($type as $v) {
            if ($v == 0) {
                break;
            } else if ($v == 1) {
                $tmp[$i]['type'] = $v;
                $tmp[$i]['text'] = $message[$i];
            } else if ($v == 2) {
                $tmp[$i]['type'] = $v;
                $tmp[$i]['width'] = $width[$i];
                $tmp[$i]['height'] = $height[$i];
                $tmp[$i]['text'] = $url[$i];
            }
            $i++;
        }

        $key = CacheKey::pushQrcodeMsg();

        if ($tmp) {
            $rs = Yii::$app->dbcache->set($key, $tmp);
        } else {
            $rs = Yii::$app->dbcache->delete($key);
        }

        if ($rs) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail();
        }
    }

    //二维码尺寸设置
    public function actionQrcodeSizeSave()
    {
        $data['width'] = Yii::$app->request->post('width');
        $data['height'] = Yii::$app->request->post('height');
        if (!($data['width'] && $data['height'])) {
            Yii::$app->message->fail('参数不完整');
        }

        $key = CacheKey::pushQrcodeSize();
        $rs = Yii::$app->dbcache->set($key, $data);

        if ($rs) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail();
        }
    }

    public function actionReset()
    {
        $type = Yii::$app->request->post('type');
        $key = '';
        if ($type == 'qrcode_msg') {
            $key = CacheKey::pushQrcodeMsg();
        } else if ($type == "qrcode_size") {
            $key = CacheKey::pushQrcodeSize();
        }
        if ($key) {
            Yii::$app->dbcache->delete($key);
        }
        Yii::$app->message->success();
    }
}