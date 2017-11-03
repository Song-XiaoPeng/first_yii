<?php

namespace apps\alicard\controllers\wap;

use portal\models\UserModel;
use Yii;
use apps\WapController;
use portal\helpers\ErrorMessage;
use apps\alicard\ar\AlicardTemplate;
use apps\alicard\ar\AlicardRecord;
use yii\db\Exception;

class GetController extends WapController
{

    const expire = 126144000;


    public function initAction($action, $require = null)
    {
        return parent::initAction($action, $require);
    }

    public function actionIndex()
    {
        //卡类型 0 通用 1 正式卡 2临时卡
        $type = Yii::$app->request->get('type', 0);
        //判断是否领取卡
        try {
            $rs = Yii::$app->aop->oauth('auth_base,auth_user,auth_ecard');
        } catch (\Exception $e) {
            return $this->redirect('\alicard\wap\get\index');
        }
        Yii::$app->session['ali_accessToken'] = $rs->access_token;
        Yii::$app->session['ali_userId'] = $rs->user_id;

        $cardRecord = AlicardRecord::find()->where(['userid' => $rs->user_id])->asArray()->one();
        $user = null;
        $hasCard = false; //是否已领卡
        if (!empty($cardRecord)) {
            $hasCard = $cardRecord['valid_date'] > time();

            !empty($cardRecord['type']) && $user = UserModel::getInfo($cardRecord['uid']);
        }
        return $this->render('index', [
            'type' => $type,
            'hasCard' => $hasCard
        ]);
    }

    /*    //正式卡
        public function actionOffical()
        {
            try {
                $rs = Yii::$app->aop->oauth('auth_base,auth_user,auth_ecard');
            } catch (\Exception $e) {
                return $this->redirect('\alicard\wap\get\index');
            }
            Yii::$app->session['ali_accessToken'] = $rs->access_token;
            Yii::$app->session['ali_userId'] = $rs->user_id;

            $cardRecord = AlicardRecord::find()->where(['userid' => $rs->user_id])->asArray()->one();
            $user = null;
            $hasCard = false; //是否已领卡
            if (!empty($cardRecord)) {
                $hasCard = $cardRecord['valid_date'] > time();
                //todo 调用支付宝查询接口，看是否被删除

                empty($cardRecord['type']) && $user = UserModel::getInfo($cardRecord['uid']);
            }

            $setting = AlicardTemplate::find()->asArray()->one();
            if (empty($setting)) {
                throw new \Exception('校园卡暂未配置');
            }
            $data = json_decode($setting['ext'], true);

            return $this->render('index', ['card' => $cardRecord, 'hasCard' => $hasCard, 'data' => $data, 'user' => $user]);
        }

        //临时卡
        public function actionTemporary()
        {
            try {
                $rs = Yii::$app->aop->oauth('auth_base,auth_user,auth_ecard');
            } catch (\Exception $e) {
                return $this->redirect('\alicard\wap\get\index');
            }
            Yii::$app->session['ali_accessToken'] = $rs->access_token;
            Yii::$app->session['ali_userId'] = $rs->user_id;

            $cardRecord = AlicardRecord::find()->where(['userid' => $rs->user_id])->asArray()->one();
            $user = null;
            $hasCard = false; //是否已领卡
            if (!empty($cardRecord)) {
                $hasCard = $cardRecord['valid_date'] > time();
                //todo 调用支付宝查询接口，看是否被删除

                empty($cardRecord['type']) && $user = UserModel::getInfo($cardRecord['uid']);
            }

            $setting = AlicardTemplate::find()->asArray()->one();
            if (empty($setting)) {
                throw new \Exception('校园卡暂未配置');
            }
            $data = json_decode($setting['ext'], true);

            return $this->render('index', ['card' => $cardRecord, 'hasCard' => $hasCard, 'data' => $data, 'user' => $user]);
        }*/
    public function actionCommit()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        if (empty($username) || empty($password)) {
            Yii::$app->message->error(ErrorMessage::ERR_ALICARD_RECIVE);
        }
        $lsCard = AlicardRecord::findOne(['cardid' => $username]);
        $user = $this->loginValid($lsCard, $username, $password);
        if (is_string($user)) {
            Yii::$app->message->fail($user);
        }

        $accessToken = Yii::$app->session['ali_accessToken'];
        $userId = Yii::$app->session['ali_userId'];
        if (empty($accessToken) || empty($userId)) {
            Yii::$app->message->error(ErrorMessage::ERR_ALIAUTH_EXPRIRED);
        }
        $res = Yii::$app->aop->call('alipay.user.info.share', [], $accessToken);
        $aliInfo = $res->alipay_user_info_share_response;
        if ($aliInfo->code != '10000') {
            Yii::$app->message->error(ErrorMessage::ERR_ALIAUTH_EXPRIRED);
        }

        $setting = AlicardTemplate::find()->asArray()->one();
        if (empty($setting)) {
            throw new \Exception('校园卡暂未配置');
        }
        $info = $this->getColumns($lsCard, $setting, $aliInfo, $user);
        $template_id = $setting['template_id'];
        $xgh = $user['number'];
        $photo = '';

        if (!empty($lsCard) && !empty($lsCard->type)) {
            $expire = $lsCard->valid_date;
        } else {
            $expire = time() + self::expire;
        }
        $rs = Yii::$app->aop->call('alipay.marketing.card.open', [
            'out_serial_no' => strval(time()),
            'card_template_id' => $template_id,
            'open_card_channel' => 'rls',
            'open_card_channel_id' => '10001',
            'card_user_info' => [
                'user_uni_id' => $userId,
                'user_uni_id_type' => 'UID',
            ],
            'card_ext_info' => [
                'open_date' => date('Y-m-d H:i:s'),
                'valid_date' => date('Y-m-d H:i:s', $expire),
                'external_card_no' => $xgh, // 我们系统的卡号
                'front_text_list' => $info,
                'front_image_id' => $photo,
            ],

        ], $accessToken);
        $response = $rs->alipay_marketing_card_open_response;
        if ($response->code === "10000") {
            if (!empty($lsCard) && !empty($lsCard->type)) {
                $alicard = $lsCard;
            } else {
                $alicard = AlicardRecord::findOne(['userid' => $userId]) ?: new AlicardRecord();
                $alicard->type = 0;
                $alicard->valid_date = time() + self::expire;
            }
            $alicard->uid = $user['uid'];
            $alicard->cardid = $xgh;
            $alicard->userid = $userId;
            $alicard->template_id = $setting['template_id'];
            $alicard->biz_card_no = $response->card_info->biz_card_no;
            $alicard->created = time();
            $alicard->save();
            Yii::$app->user->setCookie($user['uid']);
            Yii::$app->message->success();
        } else {
            error_log(var_export($response, true), 3, '/tmp/alicard_error');
            Yii::$app->message->fail();
        }
    }

    /**
     * 验证登陆信息
     * @param $card
     * @param $username string
     * @param $password string
     * @return array|mixed|string
     */
    private function loginValid($card, $username, $password)
    {
        if (!empty($card) && !empty($card->type)) { //临时卡校验本地用户名密码
            if (!Yii::$app->security->validatePassword($password, $card['password'])) {
                $user = "用户名或密码错误！";
            }
            $user = ['username' => $username, 'number' => $username, 'avatar' => '', 'uid' => 0];
        } else { //非临时卡走统一身份认证
            try {
                $uid = \UcSdk\user\User::loginInvalid($username, $password);
                $user = UserModel::getInfo($uid);
            } catch (\Exception $e) {
                $user = $e->getMessage();
            }
        }
        return $user;
    }

    /**
     * 获取提交到支付宝的字段
     * @param $card array
     * @param $setting array 模板设置信息
     * @param $aliInfo array 接口返回的阿里用户信息
     * @param $user array 咱们系统的用户信息
     * @return array
     */
    private function getColumns($card, $setting, $aliInfo, $user)
    {
        $info = [];
        if (empty($card) || empty($card->type)) {
            $cols = json_decode($setting['ext'], true)['columns'];
            $columns = Yii::$app->config->item("field.columns");
            if (in_array('name', $cols) && isset($columns['name'])) {
                $info[] = ['label' => $columns['name'], 'value' => $user['realname']];
            }
            if (in_array('xgh', $cols) && isset($columns['xgh'])) {
                $info[] = ['label' => $columns['xgh'], 'value' => $user['number']];
            }
            $identity = !empty($user['department']['identity']) ? $user['department']['identity'] : '未知';
            if (in_array('dept', $cols) && isset($columns['dept'])) {
                $info[] = ['label' => $identity == '教职工' ? '部门' : '学院', 'value' => $user['depart_name']];
            }
            if (in_array('type', $cols) && isset($columns['type'])) {
                $info[] = ['label' => '类别', 'value' => $identity == '教职工' ? '教职工卡' : '学生卡'];
            }

        } else {
            $info = [
                [
                    'label' => '姓名',
                    'value' => $aliInfo->nick_name,
                ],
                [
                    'label' => '性别',
                    'value' => $aliInfo->gender == 'm' ? '男' : '女',
                ],
                [
                    'label' => '账号',
                    'value' => $user['username'],
                ],
                [
                    'label' => '类别',
                    'value' => '临时卡',
                ],
            ];
        }
        return $info;
    }

}
