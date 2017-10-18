<?php
namespace apps\alicard\controllers\wap;

use Yii;
use apps\WapController;

class DefaultController extends WapController
{
    public function initAction($action, $require=null) 
    {
        return parent::initAction($action, $require);
    }

    public function actionIndex()
    {

    }

    
    // 直接开卡
    public function actionOpen()
    {
        $rs = Yii::$app->aop->oauth('auth_base,auth_user,auth_ecard');
        $accessToken = $rs->access_token;
        $userId = $rs->user_id;
        $res = $this->open($userId, $accessToken);
        var_dump($res);
    }
    
    public function actionInfo()
    {
        $rs = Yii::$app->aop->oauth('auth_base,auth_user,auth_ecard');
        $accessToken = $rs->access_token;
        $res = Yii::$app->aop->call('alipay.user.info.share',[],$accessToken);
        var_dump($res);
    }

    private function open($userId, $accessToken, $expire = 126144000) // 86400 * 365 * 10
    {
        $rs = Yii::$app->aop->call('alipay.marketing.card.open', [
            'out_serial_no' => strval(time()), // 唯一标识，需要修改成用自增
            'card_template_id' => '20170914000000000533022000300418', // 卡模板id
            'open_card_channel' => 'develop',
            'open_card_channel_id' => '127001',
            'card_user_info' => [
                'user_uni_id' => $userId,
                'user_uni_id_type' => 'UID',
            ],
            'card_ext_info' => [
                'open_date' => date('Y-m-d H:i:s'),
                'valid_date' => date('Y-m-d H:i:s', time() + $expire),
                'external_card_no' => 'changxing', // 我们系统的卡号, 自增
                'front_text_list' => [
                    [
                        'label' => '姓名',
                        'value' => '常兴',
                    ],
                    [
                        'label' => '学工号',
                        'value' => 'changxing',
                    ],
                    [
                        'label' => '专业',
                        'value' => '计算机科学与技术',
                    ],
                    [
                        'label' => '类别',
                        'value' => '学生卡',
                    ]
                ],
                //'front_image_id' => 'xUCxMFwVSRWqzKMsGjZq3gAAACMAAQED',
            ],
            
        ], $accessToken);
        return $rs;
        /*
        object{
            "alipay_marketing_card_open_response" => { 
                "code"=> string(5) "10000" 
                "msg"=> string(7) "Success" 
                "card_info"=> object { 
                    "biz_card_no"=> string(17) "rlstech0000000549" 
                    "external_card_no"=> string(6) "EXT002" 
                    "open_date"=> string(19) "2017-09-12 15:59:38" 
                    "valid_date"=> string(19) "2021-09-11 15:59:38" 
                } 
                "open_card_channel"=> string(7) "develop" 
                "open_card_channel_id" => string(6) "127001" 
            } 
            "sign"=> string(344) "KbK8..." 
        }
        */
    }
    public function actionUpdate(){
        $expire = 126144000;
        $rs = Yii::$app->aop->call('alipay.marketing.card.update', [
            'target_card_no' => 'cjyun0000004097',
            'target_card_no_type' => 'BIZ_CARD',
            'occur_time' => date('Y-m-d H:i:s'),
            'card_info' => [
                'template_id' => '20170920000000000550185000300417', // 卡模板id
                'open_date' => date('Y-m-d H:i:s'),
                'valid_date' => date('Y-m-d H:i:s', time() + $expire),
                'front_text_list' => [
                    [
                        'label' => '姓名',
                        'value' => '凌云',
                    ],
                    [
                        'label' => '性别',
                        'value' => '男',
                    ],
                    [
                        'label' => '账号',
                        'value' => 'daiyouyu',
                    ],
                    [
                        'label' => '类别',
                        'value' => '游客卡',
                    ],
                ],
                //'front_image_id' => 'xUCxMFwVSRWqzKMsGjZq3gAAACMAAQED',
            ],
        ]);
        var_dump($rs);
    }

    // 投放链接
    // 很奇怪, 必须用支付宝客户端打开, 否则就会提示人气太旺了报错
    public function actionApply()
    {
        $rs = Yii::$app->aop->call('alipay.marketing.card.activateurl.apply', [
            'template_id' => '20170914000000000524008000300414',
            'callback' => urlencode(Yii::$app->request->getHostInfo() . Yii::$app->urlManager->createUrl(['/alicard/wap/default/open']))
        ]);
        $url = $rs->alipay_marketing_card_activateurl_apply_response->apply_card_url;
        return $this->redirect(urldecode($url));
        /*object(stdClass)#92 (2) { ["alipay_marketing_card_activateurl_apply_response"]=> object(stdClass)#91 (3) { ["code"]=> string(5) "10000" ["msg"]=> string(7) "Success" ["apply_card_url"]=> string(274) "https%3A%2F%2Fmemberprod.alipay.com%2Faccount%2Fopenform%2Factivecard.htm%3Fapp_id%3D2017070707672608%26template_id%3D20170911000000000515841000300417%26__webview_options__%3DcanPullDown%253dNO%2526transparentTitle%253dauto%26callback%3D%2Falicard%2Fbackend%2Fdefault%2Fopen" } ["sign"]=> string(344) "Oib1/RR5kHu+Q+EM489AkjspK4FHV9ZaHCL8DU9fKYWUpDMoBQlJJJahEVbm+kJhzLQzn2yftmOjil92qBBz6/27t7uFAG+fvAWr5NgGGiVXbO/f23eVsNgpkmHnlI9Dc0Y1pCvPHqfMrsgu/hzNbEVw2UbZHfxlZ+NCDg8JfgI9iFXvoYQrBQZktDQqeHJuUIJS4LocTaKm2YlGZsyEB/WsXFOQ89/FUPT3Xc8k7CMqI9gTMudZoLFiHmMvME1Z9/6BT+kOXsVyfZAPzyyl6rpdU5BKVTXw54R43jULNpWgYfeLBtWJztiF3vz5yNZPbKeZaRXXVFdvi4P9rbRWZw==" }*/
        
    }

    // 用户填写完表单发卡
    public function actionFormOpen()
    {
        /*$request_id = Yii::$app->request->get('request_id');
        $rs = Yii::$app->aop->oauth('auth_ecard');
        $accessToken = $rs->access_token;
        $userId = $rs->user_id;
        $rs = Yii::$app->aop->call('alipay.marketing.card.activateform.query', [
            'biz_type' =>  'MEMBER_CARD',
            'template_id' => '20170911000000000515841000300417',
            'request_id' => $request_id,
        ], $accessToken);*/
        /* 返回值： infos 是创建的表单字段
        Object {
            "alipay_marketing_card_activateform_query_response"=> { 
                "code"=> string(5) "10000" 
                "msg"=> string(7) "Success" 
                "infos"=> string(76) "[{"OPEN_FORM_FIELD_MOBILE":"13552319194"},{"OPEN_FORM_FIELD_NAME":"李飞"}]" 
            } 
            "sign"=> string(344) "bHR..." 
        }*/

        // 保存表单的值
        $this->open($userId, $accessToken);
    }
    
    // 投放链接
    // 很奇怪, 必须用支付宝客户端打开, 否则就会提示人气太旺了报错
    public function actionQuery()
    {
        $rs = Yii::$app->aop->call('alipay.marketing.card.query', [
            'target_card_no' => 'cjyun0000004097',
            'target_card_no_type' => 'BIZ_CARD',
            'card_user_info' => [
                'user_uni_id' => '2088802745463971',
                'user_uni_id_type' => 'UID'
            ]
        ]);
        var_dump($rs);
    }
}
