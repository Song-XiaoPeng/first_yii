<?php

namespace core\services;

use core\models\QueueModel;
use core\models\BusinessModel;
use core\models\BusinessDistributeModel;
use core\models\DealLogModel;
use core\models\ManagerModel;
use Yii;
use core\services\Inform;

/**
 *
 */
class Queue
{

    public static function takeNumber($data)
    {

        return QueueModel::save($data);
    }

    public static function cancel($id, $device_id)
    {
        try {
            $data = QueueModel::one(['id' => $id]);
            if (empty($data)) {
                throw new \Exception("invalidate id");
            }
            if (empty($device_id) && empty(Yii::$app->user->info['id'])) {
                throw new \Exception("invalidate identity");
            }
            QueueModel::delete(['id' => $id]);
            $log = [
                'id' => $data['id'],
                'business_id' => $data['business_id'],
                'call_time' => $data['call_time'],
                'deal_time' => time(),
                'status' => 3,
            ];
            if (!empty($device_id)) {
                $log['device_id'] = $device_id;
            } else {
                $log['uid'] = Yii::$app->user->info['id'];
            }
            DealLogModel::insert($log);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function callNumber($manager_id)
    {
        $sql = 'select business_id from business_distribute where manager_id = :manager_id   ';
        $rs = Yii::$app->db->createCommand($sql, [':manager_id' => $manager_id])->queryAll();
        if (!$rs) return false;
        $arr = array_unique(array_column($rs, 'business_id'));
        $business_ids = implode(',', $arr);
        $id = 0;
        while (true) {
            //乐观锁
            $sql = 'select * from queue where business_id in (' . $business_ids . ') and status=0 order by id asc limit 1';
            $queue = Yii::$app->db->createCommand($sql)->queryOne();
            if (empty($queue)) {
                break;
            }

            $sql = 'update queue set status = 1 where id = :id';
            $result = Yii::$app->db->createCommand($sql, [':id' => $queue['id']])->execute();

            if ($result > 0) {
                $id = $queue['id'];
                break;
            }
        }
        if (!empty($id)) {
            $log = [
                'id' => $queue['id'],
                'business_id' => $queue['business_id'],
                'call_time' => $queue['call_time'],
                'deal_time' => time(),
                'manager_id' => $manager_id,
                'uid' => $queue['uid'],
                'device_id' => $queue['device_id'],
                'status' => 0,//处理中
            ];
            $rs = DealLogModel::insert($log, false);

            if (!$rs) {
                return false;
            }
        }
        $queueData = [
            'method' => 'apps.core.services.Inform.callNumberPushMessage',
            'data' => $queue,
        ];
        Yii::$app->queue->push($queueData);
        return $queue;

    }

    //帮办
    public static function callNumberByBusinessId($business_id, $manager_id)
    {
        $id = 0;
        while (true) {
            //乐观锁
            $sql = 'select * from queue where business_id = :business_id and status=0 order by id asc limit 1';
            $queue = Yii::$app->db->createCommand($sql, [':business_id' => $business_id])->queryOne();
            if (empty($queue)) {
                break;
            }

            $sql = 'update queue set status = 1 where id = :id';
            $result = Yii::$app->db->createCommand($sql, [':id' => $queue['id']])->execute();

            if ($result > 0) {
                $id = $queue['id'];
                break;
            }
        }
        if (!empty($id)) {
            $log = [
                'id' => $queue['id'],
                'business_id' => $queue['business_id'],
                'call_time' => $queue['call_time'],
                'deal_time' => time(),
                'manager_id' => $manager_id,
                'uid' => $queue['uid'],
                'device_id' => $queue['device_id'],
                'status' => 0,//处理中
            ];
            $rs = DealLogModel::insert($log, false);

            if (!$rs) {
                return false;
            }
        }
        Inform::callNumberPushMessage($queue);

        return $queue;
    }


    public static function waitingBusiness($uid)
    {
        $data = [];
        $rs = QueueModel::lists(['uid' => $uid]);
        if (!empty($rs)) {
            $data = $rs;
            $business_ids = array_unique(array_column($data, 'business_id'));
            $business = BusinessModel::lists(['id' => $business_ids]);
            $business_data = [];
            foreach ($business as $one) {
                $business_data[$one['id']] = $one;
            }
            foreach ($data as $k => &$v) {
                $v['businessInfo'] = $business_data[$v['business_id']];
                if ($v['status'] == 1) {
                    $deallog = DealLogModel::one(['id' => $v['id']]);
                    if (!empty($deallog)) {
                        $v['managerInfo'] = ManagerModel::one(['id' => $deallog['manager_id']]);
                    }
                    if ($business_data[$v['business_id']]['type'] == 2 && !empty($v['managerInfo'])) {
                        $business_distribute = BusinessDistributeModel::one(['business_id' => $v['business_id'], 'manager_id' => $v['managerInfo']['id']]);
                        if (!empty($business_distribute)) {
                            $v['businessInfo']['window'] = $business_distribute['window'];
                        }
                    }
                }

            }
        }
        return $data;
    }
}