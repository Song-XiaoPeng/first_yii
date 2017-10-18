<?php

namespace apps\pc\controllers;

use core\helpers\CacheKey;
use core\models\BusinessDistributeModel;
use core\models\BusinessModel;
use core\models\DealLogModel;
use core\models\DeviceModel;
use core\models\EvaluateManagerModel;
use core\models\ManagerModel;
use core\models\QueueModel;
use core\models\UserModel;
use core\services\Deal;
use core\services\DealLog;
use core\services\Device;
use core\services\EvaluateLog;
use core\services\Manager;
use Yii;
use core\controllers\PcController;
use core\services\User;
use core\services\Business;
use core\services\Queue;
use yii\db\Exception;

class DefaultController extends PcController
{
    public function actionTest()
    {

        $rs = Inform::WxCurrent('vincent94828', '报销');
        var_dump($rs);
    }

    public function actionIndex()
    {
        $data = [];
        $rs = DealLogModel::one(['manager_id' => Yii::$app->manager->info['id'], 'status' => 0]);

        if ($rs) {
            $data = $rs;
            $data['businessInfo'] = BusinessModel::one(['id' => $data['business_id']]);
            $data['lock'] = true;
            if ($rs['uid']) {
                $user = UserModel::one(['id' => $data['uid']]);
                $data['average'] = !empty($user['count']) ? intval($user['total'] / $user['count']) : 0;
                $data['user'] = $user['nickname'] ? $user['nickname'] : '未填写';
                $data['cellphone'] = $user['cellphone'] ? $user['cellphone'] : '未填写';
            }
        }
        $key = CacheKey::pcStatus(Yii::$app->manager->info['id']);
        $value = Yii::$app->dbcache->get($key);
        $status = empty($value) ? 0 : 1;
        //背景图片
        $img_user = Yii::$app->dbcache->get(CacheKey::pcBackground());
        $bg_img = $img_user ? \Misc::get('common.schema') . \Misc::get('common.imghost') . '/' . $img_user : \Misc::get('common.schema') . \Misc::get('common.host') . '/' . 'pc/images/bg_index.png';
        //当天办理业务数量
        $deal_numbers = DealLog::dealNumbers(Yii::$app->manager->uid);

        $key = CacheKey::managerWindow(Yii::$app->manager->info['id']);
        $window = Yii::$app->dbcache->get($key);
//        d($data);
        return $this->render('index', [
            'data' => $data,
            'status' => $status,
            'window' => $window,
            'bg_img' => $bg_img,
            'deal_numbers' => $deal_numbers['number']
        ]);
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

    //叫号
    public function actionCallNumber()
    {
        $manager_id = Yii::$app->manager->info['id'];

        $business_exist = DealLog::nowBusinessExist($manager_id);
        if ($business_exist) Yii::$app->message->fail('叫号失败，当前还有处理中的业务');

        $queue = Queue::callNumber($manager_id);//
        if (empty($queue)) {
            Yii::$app->message->fail('没有预约的业务');
        }
        //拿到id后查询，业务相关，下一个等待的业务、预约号，几人在等待等等用于页面展示的数据
        $data = [
            'name' => BusinessModel::one(['id' => $queue['business_id']])['name'],
            'id' => $queue['id'],
        ];

        if ($queue['uid']) {
            $user = UserModel::one(['id' => $queue['uid']]);
            $data['user'] = $user['nickname'] ? $user['nickname'] : '未填写';
            $data['cellphone'] = $user['cellphone'] ? $user['cellphone'] : '未填写';
            $data['average'] = !empty($user['count']) ? intval($user['total'] / $user['count']) : 0;
        }

        if (!empty($data))
            Yii::$app->message->success($data);
    }

    //完成
    public function actionFinish()
    {
        //update deallog
        //delete queue
        $tran = Yii::$app->db->beginTransaction();
        try {
            $id = Yii::$app->request->post('id');
            $one = DealLogModel::one(['id' => $id]);
            if ($one['status'] == DealLogModel::CANCEL) Yii::$app->message->success('业务已取消办理');
            if ($one['status'] != DealLogModel::DOING) throw new Exception('要处理的业务不存在');

            $deal_rs = DealLogModel::update(['status' => DealLogModel::DONE, 'finish_time' => time()], ['id' => $id]);//处理完成
            if (!$deal_rs) throw new Exception('更新业务处理状态失败');
            $exist = QueueModel::one(['id' => $id]);
            if ($exist) {
                $queue_rs = QueueModel::delete(['id' => $id]);
                if (!$queue_rs) throw new Exception('删除预约业务列表失败');
            }
            $tran->commit();
            $data['number'] = $this->dealNum()['number'];
            $data['is_user'] = empty($one['divice_id']) && $one['uid'];
            Yii::$app->message->success($data);
        } catch (\Exception $e) {
            $tran->rollBack();
            Yii::$app->message->fail($e->getMessage());
        }
    }

    //过号
    public function actionPass()
    {
        //update deallog
        //delete queue
        $tran = Yii::$app->db->beginTransaction();
        try {
            $id = Yii::$app->request->post('id');
            $one = DealLogModel::one(['id' => $id]);
            if ($one['status'] == DealLogModel::CANCEL) Yii::$app->message->success('业务已取消办理');
            if ($one['status'] != DealLogModel::DOING) throw new Exception('要处理的业务不存在');
            $deal_rs = DealLogModel::update(['status' => DealLogModel::OVER, 'finish_time' => time()], ['id' => $id]);//过号
            if (!$deal_rs) throw new Exception('更新业务处理状态失败');
            $exist = QueueModel::one(['id' => $id]);
            if ($exist) {
                $queue_rs = QueueModel::delete(['id' => $id]);
                if (!$queue_rs) throw new Exception('删除预约业务列表失败');
            }
            $tran->commit();
            Yii::$app->message->success();
        } catch (\Exception $e) {
            $tran->rollBack();
            Yii::$app->message->fail($e->getMessage());
        }
    }

    //已办理预约记录
    public function actionDealLogs()
    {
        $start = Yii::$app->request->get('start');
        $end = Yii::$app->request->get('end');
        $pageSize = Yii::$app->request->get('page_size');
        Yii::$app->page->pagesize = $pageSize;
        $limit = Yii::$app->page->getLimit();
        list($offset, $limit) = $limit;
        $where = [];
        if ($start && !$end) $where[] = ['>=', 'finish_time', strtotime($start)];
        if (!$start && $end) $where[] = ['<=', 'finish_time', strtotime($end) + 24 * 3600];
        if ($start && $end) $where[] = [
            'AND',
            ['>=', 'finish_time', strtotime($start)],
            ['<=', 'finish_time', strtotime($end) + 24 * 3600],
        ];
        $manager_id = Yii::$app->manager->info['id'];

        array_push($where, ['manager_id' => $manager_id]);
        if (count($where) <= 1)
            $where = array_shift($where);
        else
            array_unshift($where, 'AND');

        $lists = DealLogModel::lists($where, $offset, $limit, ['deal_time DESC']);

        $uids = array_unique(array_column($lists['lists'], 'uid'));
        $user_lists = UserModel::lists(['id' => $uids]);
        $user_lists = array_column($user_lists, 'nickname', 'id');

        $device_ids = array_unique(array_column($lists['lists'], 'device_id'));
        $device_lists = Device::listsById($device_ids);
        $device_lists = array_column($device_lists, 'name', 'id');

        $business_ids = array_unique(array_column($lists['lists'], 'business_id'));
        $business_lists = BusinessModel::lists(['id' => $business_ids]);
        $business_lists = array_column($business_lists, 'name', 'id');

        $status = [
            '0' => '处理中',
            '1' => '处理完成',
            '2' => '过号',
            '3' => '取消',
            '4' => '已评价'
        ];

        foreach ($lists['lists'] as &$v) {
            $v['user_name'] = !empty($user_lists[$v['uid']]) ? $user_lists[$v['uid']] : '';
            $v['device_name'] = !empty($device_lists[$v['device_id']]) ? $device_lists[$v['device_id']] : '';
            $v['name'] = !empty($v['device_name']) ? $v['device_name'] : $v['user_name'];
            $v['business_name'] = $business_lists[$v['business_id']];
            $v['status'] = $status[$v['status']];
            $v['start'] = date('Y-m-d H:i:s', $v['call_time']);
            $v['end'] = date('Y-m-d H:i:s', $v['finish_time']);
        }

        Yii::$app->message->success($lists);
    }

    //评价
    public function actionEvaluate()
    {
        $manager_id = Yii::$app->manager->uid;
        $desc = Yii::$app->request->post('desc');
        $score = Yii::$app->request->post('score');
        $deal_id = Yii::$app->request->post('deal_id');
        $deal_log = DealLogModel::one(['id' => $deal_id]);
        $uid = $deal_log['uid'];
        $device_id = $deal_log['device_id'];
        //如果是设备
        if (!$uid && $device_id) Yii::$app->message->success();

        if ($score <= 1 && empty($desc)) Yii::$app->message->fail('评分小于1分需要填写评价内容');
        if (iconv_strlen($desc) > 255) Yii::$app->message->fail('评价内容长度应该在255个字符以内');
        if (EvaluateManagerModel::one(['deal_id' => $deal_id])) Yii::$app->message->fail('已评加该业务，不能重复评价');

        $tran = Yii::$app->db->beginTransaction();
        try {
            $data = [
                'manager_id' => $manager_id,
                'desc' => $desc,
                'score' => $score,
                'deal_id' => $deal_id,
                'uid' => $uid,
                'createtime' => time()
            ];
            $evaluate_rs = EvaluateManagerModel::insert($data);
            if (!$evaluate_rs) throw new Exception('评价保存失败');
            unset($data);

            $score_rs = User::updateScore($score, $uid);
            if (!$score_rs) throw new Exception('用户评分保存失败');

            $tran->commit();
            Yii::$app->message->success('业务评价成功');
        } catch (\Exception $e) {
            $tran->rollBack();
            Yii::$app->message->fail($e->getMessage());
        }
    }

    public function actionBase64()
    {
        $category = 'image';
        $base_64 = Yii::$app->request->post('src');
        $rs = preg_match('/^data:\s*image\/(\w+);base64,(.+)/', $base_64, $match);
        if (!$rs) {
            Yii::$app->message->fail('上传失败');
        }
        $fileId = Yii::$app->idgenter->create($category);
        $fileName = $fileId . '.' . $match[1];
        $stream = base64_decode($match[2]);
        $upload_dir = \Misc::get('common.data') . '/' . $category . '/' . floor($fileId / 1000) . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $rs = file_put_contents($upload_dir . $fileName, $stream);
        if (!$rs) {
            Yii::$app->message->fail('上传失败');
        }
        $rs = ManagerModel::save([
            'id' => Yii::$app->manager->info['id'],
            'headimg' => $category . '/' . floor($fileId / 1000) . '/' . $fileName,
        ]);
        if (!$rs) {
            Yii::$app->message->fail('上传失败');
        }

        $imgInfo = getimagesize($upload_dir . $fileName);
        $data = [
            'title' => $fileName,
            'url' => Yii::$app->view->imghost . '/' . $category . '/' . floor($fileId / 1000) . '/' . $fileName,
            'size' => $rs,
            'width' => $imgInfo['0'],
            'height' => $imgInfo['1'],
        ];
        Yii::$app->message->success($data);

    }

    //已预约业务列表
    public function actionQueueLists()
    {
        $id = Yii::$app->manager->uid;
        $pageSize = Yii::$app->request->get('page_size');

        $business_ids = BusinessDistributeModel::assoc(['manager_id' => $id], 'business_id', 'business_id');
        $business_ids = array_unique($business_ids);

        Yii::$app->page->pagesize = $pageSize;
        $limit = Yii::$app->page->getLimit();
        list($offset, $limit) = $limit;
        $queue = QueueModel::lists(['business_id' => $business_ids, 'status' => QueueModel::STATUS_WAITING], $offset, $limit, ['id ASC']);
        Yii::$app->page->setTotal($queue['count']);
        $queue['lists'] = DealLog::queueDetails($queue['lists']);

        Yii::$app->message->success($queue);
    }

    public function actionNext()
    {
        $manager_id = Yii::$app->manager->info['id'];
        $business_ids = Business::managerId2BusinessIds($manager_id);
        $rs = QueueModel::lists(['business_id' => $business_ids, 'status' => 0], 0, 1, ['id asc']);
        $data = [
            'data' => [],
            'count' => 0
        ];
        if ($rs['count'] != 0) {
            $data['data'] = reset($rs['lists']);
            $data['data']['businessInfo'] = BusinessModel::one(['id' => $data['data']['business_id']]);
            $data['count'] = $rs['count'];
        }
        Yii::$app->message->success($data);
    }

    public function actionStart()
    {
        $start = Yii::$app->request->post('start');
        $key = CacheKey::pcStatus(Yii::$app->manager->info['id']);
        if ($start) {
            Yii::$app->dbcache->set($key, 1);
        } else {
            Yii::$app->dbcache->delete($key);
        }
        Yii::$app->message->success();
    }

    /**
     * 帮办列表
     */
    public function actionHelpLists()
    {
        $pageSize = Yii::$app->request->get('page_size');
        $manager_id = Yii::$app->manager->uid;

        Yii::$app->page->pagesize = $pageSize;
        $limit = Yii::$app->page->getLimit();
        list($offset, $limit) = $limit;

        //查出自己负责的业务
        $self_business_ids = BusinessDistributeModel::selfBusinessIds($manager_id);
        /* $where = [
        'AND', ['!=', 'business_id', $business_ids], ['status' => QueueModel::STATUS_WAITING]
         ];*/
//        d($where);

        $lists = QueueModel::getHelpLists(['status' => QueueModel::STATUS_WAITING], $offset, $limit, ['id ASC']);

        Yii::$app->page->setTotal($lists['count']);
        $business_ids = array_column($lists['lists'], 'business_id');
        $business = BusinessModel::assoc(['id' => $business_ids], 'id', 'name');

        foreach ($lists['lists'] as $k => &$v) {
            if (in_array($v['business_id'], $self_business_ids)) {
                unset($lists['lists'][$k]);
                $lists['count']--;
                continue;
            }
            $v['name'] = $business[$v['business_id']];
        }
        Yii::$app->message->success($lists);
    }

    public function actionHelp()
    {
        $business_id = Yii::$app->request->post('business_id');
        $manager_id = Yii::$app->manager->info['id'];

        $business_exist = DealLog::nowBusinessExist($manager_id);
        if ($business_exist) Yii::$app->message->fail('叫号失败，当前还有处理中的业务');

        $queue = Queue::callNumberByBusinessId($business_id, $manager_id);
        //验证当前是否有正在办理的业务

        if (empty($queue)) {
            Yii::$app->message->fail('该业务已被他人办理');
        }
        $data = [
            'name' => BusinessModel::one(['id' => $queue['business_id']])['name'],
            'id' => $queue['id'],
        ];
        if (!empty($queue['uid'])) {
            $score = UserModel::one(['id' => $queue['uid']]);
            $data['average'] = !empty($score['count']) ? intval($score['total'] / $score['count']) : 0;
        }

        if (!empty($data))
            Yii::$app->message->success($data);
    }


    public function actionSetWindow()
    {
        $key = CacheKey::managerWindow(Yii::$app->manager->info['id']);
        $window = Yii::$app->request->post('window');
        Yii::$app->dbcache->set($key, $window);
        Yii::$app->message->success();
    }

    public function dealNum()
    {
        //当天办理业务数量
        $deal_numbers = DealLog::dealNumbers(Yii::$app->manager->uid);
        return $deal_numbers;
    }
}
