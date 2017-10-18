<?php

namespace apps\api\controllers;

use core\controllers\ApiController;
use core\models\DealLogModel;
use core\models\EvaluateLogModel;
use core\models\EvaluateScoreModel;
use core\models\EvaluateTagModel;
use core\models\TagsModel;
use core\services\Business;
use core\services\EvaluateLog;
use core\services\EvaluateScore;
use core\services\Manager;
use core\services\Tags;
use Yii;
use core\models\DeviceModel;
use core\helpers\ErrorMessage;
use core\models\DeviceBusinessModel;
use core\models\BusinessModel;
use core\helpers\CacheKey;
use core\services\Socket;
use lib\tools\verify\JSign;
use yii\db\Exception;
use core\services\Queue;

/**
 *
 */
class DeviceController extends ApiController
{
    public $layout = false;

    public function actionTest()
    {

        $device_id = Yii::$app->request->post('device_id', 123);

        $params = [
            'timestrap' => time(),
        ];

        $jsign = new JSign();
        $key = \Misc::get('params.appkey');
        $data = [
            'voice' => '请' . rand(1, 5000) . '号到' . rand(1, 20) . '号窗口',
            'businessUpdate' => [
                [
                    'business_id' => 102,
                    'queueCount' => 23,
                ]
            ],
            'timestrap' => $params['timestrap'],
            'signature' => $jsign->getSign($params, $key),
        ];

        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $rs = Socket::send($json, $device_id);
        Yii::$app->message->success($rs);
    }

    //评价页面
    public function actionIndex()
    {
        Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_HTML;
        $secret = Yii::$app->request->get('secret');
        $key = CacheKey::evaluateSecret($secret);
        $deal_id = Yii::$app->dbcache->get($key);
        if (!$deal_id) {
            return $this->render('error');
        }
        $one = DealLogModel::one(['id' => $deal_id, 'status' => 1]);
        if (!$one) {
            return $this->render('error');
        }
        //管理员基本信息
        $manager_info = Manager::managerInfo($one['manager_id']);
        $manager_info['avg'] = !empty($manager_info['count']) ? intval($manager_info['total'] / $manager_info['count']) : 5;
        //管理员的评价内容列表
        $eva_logs = EvaluateLog::uid2lists($one['manager_id']);

        //评分项详情
        $score_details = EvaluateScore::uid2scoreElements($one['manager_id']);

        //标签列表
        $tags = Tags::lists();

        return $this->render('index', [
            'secret' => $secret,
            'manager' => $manager_info,
            'eva_log' => $eva_logs,
            'score_details' => $score_details,
            'tags' => $tags,
        ]);
    }

    //评价
    public function actionEvaluate()
    {
        $secret = Yii::$app->request->post('secret');
        if (!$secret) Yii::$app->message->fail('参数有误，您要评价的业务不存在');
        $key = CacheKey::evaluateSecret($secret);
        $deal_id = Yii::$app->dbcache->get($key);
        if (!$deal_id) Yii::$app->message->fail('参数有误，您要评价的业务不存在');
        $one = DealLogModel::one(['id' => $deal_id]);

        if (!$one) Yii::$app->message->fail('参数有误，要评价的业务不存在');
        if ($one['status'] == 4) Yii::$app->message->fail('您已对该次业务做出评价，无需重复评价');

        $manager_id = $one['manager_id'];
        $business_id = $one['business_id'];
        $score = Yii::$app->request->post('score');
        $tags = Yii::$app->request->post('tags');
        $desc = Yii::$app->request->post('desc');

        $tran = Yii::$app->db->beginTransaction();
        try {
            if ($score) {
                $total = 0;
                $count = 0;
                foreach ($score as $v) {//[{id:1,score:5},{id:2,score:4}]
                    $data['deal_id'] = $deal_id;
                    $data['business_id'] = $business_id;
                    $data['manager_id'] = $manager_id;
                    $data['createtime'] = time();
                    $data['score_id'] = $v['id'];
                    $data['score'] = $v['score'];
                    $eva_score_id = EvaluateScoreModel::insert($data);
                    if (!$eva_score_id) throw new Exception('评分项保存失败');
                    $total += $v['score'];
                    $count++;
                }
                $total_avg = $total / $count;
                //保存管理员的平均的份
                $manager_score = Manager::updateScore($total_avg, $manager_id);
                if (!$manager_score) throw new Exception('管理员评分保存失败');

                //保存处室 大厅 学院的平均得分
                $one = BusinessModel::one(['id' => $business_id]);
                if ($one['college_id']) {
                    $place_score = Business::updateCollegeScore($total_avg, $one['college_id']);
                    if (!$place_score) throw new Exception('学院评分保存失败');
                } elseif ($one['hall_id']) {
                    $place_score = Business::updateHallScore($total_avg, $one['hall_id']);
                    if (!$place_score) throw new Exception('大厅评分保存失败');
                } elseif ($one['office_id']) {
                    $place_score = Business::updateOfficeScore($total_avg, $one['office_id']);
                    if (!$place_score) throw new Exception('处室评分保存失败');
                }
            }
            unset($data);
            unset($relative_data);
            if ($tags) {
                foreach ($tags as $v) {
                    $data['tags_id'] = $v;
                    $data['deal_id'] = $deal_id;
                    $eva_tag_rs = EvaluateTagModel::insert($data);
                    if (!$eva_tag_rs) throw new Exception('用户标签保存失败');
                    Tags::updateFrequency($v);
                }
            }
            unset($data);
            if ($desc) {
                $data['desc'] = $desc;
                $data['deal_id'] = $deal_id;
                $data['manager_id'] = $manager_id;
                $data['createtime'] = time();
                $eva_log_rs = EvaluateLogModel::insert($data);
                if (!$eva_log_rs) throw new Exception('用户标签保存失败');
            }
            //更新处理记录为已处理
            DealLogModel::update(['status' => 4], ['id' => $deal_id]);
            Yii::$app->dbcache->delete($key);
            $tran->commit();
            Yii::$app->message->success();
        } catch (\Exception $e) {
            $tran->rollBack();
            Yii::$app->message->fail($e->getMessage());
        }
    }

    //绑定
    public function actionBind()
    {

        $IMEI = Yii::$app->request->post('IMEI');
        $signature = Yii::$app->request->post('signature');

        if (empty($IMEI)) {
            Yii::$app->message->error(ErrorMessage::EMPTY_IMEI);
        }
        $data = [
            'IMEI' => $IMEI,
        ];
        $key = \Misc::get('params.appkey');
        $sign = JSign::getSign($data, $key);
        if ($signature !== $sign) {
            Yii::$app->message->error(ErrorMessage::SIGNATURE_ERROR);
        }
        $device = DeviceModel::one(['IMEI' => $IMEI]);
        if (!empty($device)) {
            Yii::$app->message->error(ErrorMessage::ALREADY_BIND, [], ['deviceId' => $device['id']]);
        }
        $id = DeviceModel::insert([
            'IMEI' => $IMEI,
            'createtime' => time(),
        ]);
        if (!$id) {
            Yii::$app->message->error(ErrorMessage::DB_ERROR);
        }
        Yii::$app->message->success(['deviceId' => $id]);
    }

    //业务列表
    public function actionBusiness()
    {

        $IMEI = Yii::$app->request->post('IMEI');
        if (empty($IMEI)) {
            Yii::$app->message->error(ErrorMessage::EMPTY_IMEI);
        }
        $device = DeviceModel::one(['IMEI' => $IMEI]);
        if (empty($device)) {
            Yii::$app->message->error(ErrorMessage::NOT_BIND);
        }
        if ($device['status'] == 0) {
            Yii::$app->message->error(ErrorMessage::NOT_ACTIVE);
        }
        $device_business = DeviceBusinessModel::lists(['device_id' => $device['id']]);
        if (empty($device_business)) {
            Yii::$app->message->success(['businessList' => []]);
        }
        $business_ids = array_column($device_business, 'business_id');
        $sql = 'select business_id,count(id) as `count` from `queue` where business_id in (' . implode(',', $business_ids) . ') group by business_id';
        $rs = Yii::$app->db->createCommand($sql)->queryAll();
        $data = [];
        foreach ($rs as $one) {
            $data[$one['business_id']] = $one;
        }
        $business = BusinessModel::lists(['id' => $business_ids]);
        foreach ($business as &$one) {
            $one['queueCount'] = isset($data[$one['id']]) ? $data[$one['id']]['count'] : 0;
        }
        Yii::$app->message->success(['businessList' => $business]);

    }

    //取号
    public function actionBespeak()
    {
        $IMEI = Yii::$app->request->post('IMEI');
        $business_id = Yii::$app->request->post('businessId');
        $signature = Yii::$app->request->post('signature');
        $data = [
            'IMEI' => $IMEI,
            'businessId' => $business_id,
        ];
        $key = \Misc::get('params.appkey');
        $jsign = new JSign();
        $sign = $jsign->getSign($data, $key);
        if ($signature !== $sign) {
            Yii::$app->message->error(ErrorMessage::SIGNATURE_ERROR);
        }

        if (empty($IMEI)) {
            Yii::$app->message->error(ErrorMessage::EMPTY_IMEI);
        }
        if (empty($business_id)) {
            Yii::$app->message->error(ErrorMessage::EMPTY_BUSINESS_ID);
        }
        $device = DeviceModel::one(['IMEI' => $IMEI]);
        if (empty($device)) {
            Yii::$app->message->error(ErrorMessage::NOT_BIND);
        }
        if ($device['status'] == 0) {
            Yii::$app->message->error(ErrorMessage::NOT_ACTIVE);
        }
        $business = BusinessModel::one(['id' => $business_id]);
        if (empty($business)) {
            Yii::$app->message->error(ErrorMessage::BUSINESS_NOT_FOUND);
        }
        $time = time();
        $id = Queue::takeNumber([
            'business_id' => $business_id,
            'call_time' => $time,
            'device_id' => $device['id']
        ]);
        if (!$id) {
            Yii::$app->message->error(ErrorMessage::DB_ERROR);
        }
        $sql = 'select count(*) as count from `queue` where business_id = :business_id and id < :id';
        $rs = Yii::$app->db->createCommand($sql, [':business_id' => $business_id, ':id' => $id])->queryOne();
        $count = $rs['count'];
        $secret = md5(getRandChar(18) . $id);
        $key = CacheKey::evaluateSecret($secret);
        Yii::$app->dbcache->set($key, $id);
        $url = Yii::$app->urlManager->createAbsoluteUrl(['/api/device', 'secret' => $secret]);
        $data = self::getData($id, $count, $business, $time, $url);
        Yii::$app->message->success($data);
    }

    //用户失焦添加标签
    public function actionAddTag()
    {
        $secret = Yii::$app->request->post('secret');
        if (empty($secret)) Yii::$app->message->fail('参数错误');

        $key = CacheKey::evaluateSecret($secret);
        $deal_id = Yii::$app->dbcache->get($key);
        if (!$deal_id) Yii::$app->message->fail('参数有误');
        if (DealLogModel::one(['id' => $deal_id, 'status' => 4])) Yii::$app->message->fail('参数有误');

        $data['name'] = trim(Yii::$app->request->post('name'));
        $data['createtime'] = time();
        if (!$data['name']) Yii::$app->message->fail('标签名不能为空');
        $one = TagsModel::one(['name' => $data['name']]);
        if ($one) {
            Yii::$app->message->success($one['id']);
        }

        $rs = TagsModel::insert($data);
        if ($rs)
            Yii::$app->message->success($rs);
        else
            Yii::$app->message->fail();
    }

    public function getData($queueId, $queueCount, $business, $time, $url)
    {
        $tmpArr = [
            "{wait}" => $queueCount,
            "{business}" => $business['name'],
            "{queue_id}" => $queueId,
        ];

        $msg_key = CacheKey::pushQrcodeMsg();
        $msg_template = Yii::$app->dbcache->get($msg_key);
        /*
                $qrcode_size_key = CacheKey::pushQrcodeSize();
                $size = Yii::$app->dbcache->get($qrcode_size_key);
                $width = $size ? $size['width'] : 200;
                $height = $size ? $size['height'] : 200;*/

        if ($msg_template) {
            array_walk($msg_template, function (&$v) use ($tmpArr, $url) {
                if ($v['type'] == 1) {
                    $v_tmp = preg_replace_callback("/\{\w+\}/", function ($match) use ($tmpArr) {
                        return $tmpArr[$match[0]];
                    }, $v['text']);
                    $v['text'] = self::completion($v_tmp);
                } else if ($v['type'] == 2) {
                    $v['text'] = $v['text'] ? $v['text'] : $url;
                }
            });
        } else {
            $msg_template = [
                [
                    'type' => 1,
                    'text' => self::completion('北航师生服务大厅欢迎您！'),
                ],
                [
                    'type' => 1,
                    'text' => self::completion('···············'),
                ],
                [
                    'type' => 1,
                    'text' => self::completion('预约号:' . $queueId),
                ],
                [
                    'type' => 1,
                    'text' => self::completion('等待人数:' . $queueCount),
                ],
                [
                    'type' => 1,
                    'text' => self::completion('预约业务:' . $business['name']),
                ],
                [
                    'type' => 1,
                    'text' => self::completion('···············'),
                ],
                [
                    'type' => 1,
                    'text' => self::completion('欢迎扫描二维码进行评价！'),
                ],
                [
                    'type' => 2,
                    'text' => $url,
                    'width' => 200,
                    'height' => 200,
                ]
            ];
        }
        /*


                $qrcode_size = [
                    'type' => 2,
                    'text' => $url,
                    'width' => $width,
                    'height' => $height,
                ];

                array_push($msg_template, $qrcode_size);*/

        $data = [
            'site' => $msg_template
        ];

        return $data;
    }

    public function completion($str)
    {
        $length = mb_strlen($str, 'utf-8');

        $left = ceil((15 - $length) / 2);
        $right = floor((15 - $length) / 2);
        $new_str = '';
        for ($i = 0; $i < $left; $i++) {
            $new_str .= ' ';
        }
        $new_str .= $str;
        for ($i = 0; $i < $right; $i++) {
            $new_str .= ' ';
        }
        return $new_str;
    }

    public function tmpArr($wait = '等待人数', $user = '用户名称', $require = 'require', $business = "业务名称", $queue_id = "预约号")
    {
        return [
            "{wait}" => $wait,
            "{user}" => $user,
            "{require}" => $require,
            "{business}" => $business,
            "{queue_id}" => $queue_id,
        ];
    }

    //查看更多
    public function actionMore()
    {
        $secret = Yii::$app->request->get('secret');
        $pagesize = Yii::$app->request->get('pagesize');
        $key = CacheKey::evaluateSecret($secret);
        if (empty($secret) || empty($key)) Yii::$app->message->fail('参数错误');

        $deal_id = Yii::$app->dbcache->get($key);
        $one = DealLogModel::one(['id' => $deal_id]);
        Yii::$app->page->pagesize = $pagesize;
        $limit = Yii::$app->page->getLimit();

        $rs = EvaluateLog::uid2lists($one['manager_id'], $limit[0], $limit[1]);

        $rs['pagesize'] = $pagesize;
        Yii::$app->page->setTotal($rs['count']);
        Yii::$app->message->success($rs);
    }
}