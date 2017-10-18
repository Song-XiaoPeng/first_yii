<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 17/08/22
 * Time: 19:16
 */

namespace core\services;

use core\helpers\CacheKey;
use core\models\BusinessModel;
use core\models\BusinessDistributeModel;
use core\models\CollegeModel;
use core\models\DealLogModel;
use core\models\HallModel;
use core\models\OfficeModel;
use core\models\UserModel;
use Yii;

/*
 * -- 数据统计
 *    -- 注册人数
 * */

class Statistics
{

    //=====
    //注册
    //====
    //注册人数 按周，月，年分
    public static function UserRegisterNumberByType($type = 'month')
    {
        switch ($type) {
            case 'week':
                $format = '%Y-%U';
                break;
            case 'month':
                $format = '%Y-%m';
                break;
            case 'year':
                $format = '%Y';
                break;
            default:
                return false;
        }
        $sql = "select date_format(from_unixtime(createtime),'{$format}') as time,regist_type,count(id) as total from user group by time,regist_type ORDER BY `time`";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        return self::details($list);
    }

    //获得注册类型
    public static function getRegistType()
    {
        return [
            '0' => '手机号',
            '1' => 'QQ',
            '2' => '微信',
            '3' => '企业号'
        ];
    }

    //获得详细的注册信息
    public static function details($list)
    {
        $regist_type = self::getRegistType();
        array_walk($list, function (&$v) use ($regist_type) {
            $v['type'] = $regist_type[$v['regist_type']];
        });
        $tmp = [];
        $total = 0;
        foreach ($list as $v) {
            $total += $v['total'];
            $count = 0;
            $k = $v['time'];
            $children = [];
            if (array_key_exists($k, $tmp)) break;
            foreach ($list as $v1) {
                if ($v['time'] == $v1['time']) {
                    $count += $v1['total'];
                    $children[$v1['regist_type']] = $v1;
                }
            }
            $tmp[$k]['total'] = $count;
            $tmp[$k]['time'] = $k;
            $tmp[$k]['children'] = $children;
        }
        $result['list'] = $tmp;
        $result['total'] = $total;
        return $result;
    }

    //=====
    //预约
    //====
    //预约数量 按周，月，年
    public static function businessNumberByType($type = 'month')
    {
        switch ($type) {
            case 'week':
                $format = '%Y-%U';
                break;
            case 'month':
                $format = '%Y-%m';
                break;
            case 'year':
                $format = '%Y';
                break;
            default:
                return false;
        }
        $sql = "select *,date_format(from_unixtime(call_time),'{$format}') as time,business_id,`status`,count(*) as total from deal_log group by time,business_id ,`status` order by time desc";
        $list = Yii::$app->db->createCommand($sql)->queryAll();
        $business_id = array_unique(array_column($list, 'business_id'));
        $businesses = Business::listByBusinessIds($business_id);
        array_walk($list, function (&$v) use ($businesses) {
            $v['dependence_name'] = $businesses[$v['business_id']]['dependence_name']['name'];
        });

        /*$tmp = self::businessDetails($list, 'time');
        $de = self::de($tmp);
        $rs = self::to($de);*/
        //统计各个分组的总数
        $list = self::ea($list);
        if ($type == 'week') {
            $tmp = [];
            foreach ($list as $k => $v) {
                $date = self::get_week(substr($k, 0, strpos($k, '-')));
                if (strpos($k, '-')) {
                    $tmp[$date[substr($k, strpos($k, '-') + 1)]] = $v;
                    $tmp['week'] = substr($k, strpos($k, '-') + 1);
                }
            }
            $list = $tmp;
        }

        return $list;
    }

    /*    //按照部门分组 按照日期、部门、status进行分组
        public static function businessDetails($list, $key, $tmp = [])
        {
            //按照日期，部门分组
            foreach ($list as $v) {
                $k = $v[$key];
                if (array_key_exists($k, $tmp)) continue;
                $children = [];
                foreach ($list as $v1) {
                    if ($k == $v1[$key]) {
                        $children[] = $v1;
                    }
                }
                $tmp[$k] = $children;
            }
            return $tmp;
        }

        //按照部门名称分
        public static function de($list)
        {
            $t = [];
            foreach ($list as $k => $v) {
                $t[$k] = self::businessDetails($v, 'dependence_name', $tmp = []);
            }
            return $t;
        }

        //按照业务预约状态分 预约数量 实际办理数量 线上数量 线下预约数量
        public static function to($list)
        {
            $t = [];
            foreach ($list as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    $t[$k][$k1] = self::businessDetails($v1, 'status', $tmp = []);
                }
            }
            return $t;
        }*/

    //统计各个分组的总数
    public static function ea($list)
    {
        $tmp = [];
        $k_time = array_unique(array_column($list, 'time'));
        $k_dependence = array_unique(array_column($list, 'dependence_name'));
        $ttt = 0;
        $ttt_finish = 0;
        $ttt_off = 0;
        $ttt_eva = 0;
        foreach ($k_time as $v) {
            $tt = 0;
            $tt_finish = 0;
            $tt_off = 0;
            $tt_eva = 0;
            foreach ($k_dependence as $v1) {
                $t = 0;
                $t_finish = 0;
                $t_off = 0;
                $t_eva = 0;
                foreach ($list as $v2) {
                    if ($v2['time'] == $v && $v2['dependence_name'] == $v1) {
                        $t += $v2['total'];
                        if (in_array($v2['status'], [1, 4])) $t_finish += $v2['total'];
                        if ($v2['status'] == 4) $t_eva += $v2['total'];
                        if ($v2['device_id']) $t_off += $v2['total'];
                    }
                }
                $tmp[$v][$v1]['t'] = $t;//预约数量
                $tmp[$v][$v1]['t_finish'] = $t_finish;//实际办理数量
                $tmp[$v][$v1]['t_offline'] = $t_off;//线上预约数量
                $tmp[$v][$v1]['t_online'] = $t - $t_off;//线下预约数量
                $tmp[$v][$v1]['t_eva'] = $t_eva;//评价数量
                $tt += $t;
                $tt_finish += $t_finish;
                $tt_off += $t_off;
                $tt_eva += $t_eva;
            }
            $tmp[$v]['t'] = $tt;
            $tmp[$v]['t_finish'] = $tt_finish;
            $tmp[$v]['t_offline'] = $tt_off;
            $tmp[$v]['t_online'] = $tt - $tt_off;
            $tmp[$v]['t_eva'] = $tt_eva;
            $ttt += $tt;
            $ttt_finish += $tt_finish;
            $ttt_off += $tt_off;
            $ttt_eva += $tt_eva;
        }
        $tmp['t'] = $ttt;
        $tmp['t_finish'] = $ttt_finish;
        $tmp['t_offline'] = $ttt_off;
        $tmp['t_online'] = $ttt - $ttt_off;
        $tmp['t_eva'] = $ttt_eva;
        return $tmp;
    }

    public static function get_week($year)
    {
        $year_start = $year . "-01-01";
        $year_end = $year . "-12-31";
        $startday = strtotime($year_start);
        if (intval(date('N', $startday)) != '1') {
            $startday = strtotime("next monday", strtotime($year_start)); //获取年第一周的日期 
        }
        $year_mondy = date("Y-m-d", $startday); //获取年第一周的日期
        $endday = strtotime($year_end);
        if (intval(date('W', $endday)) == '7') {
            $endday = strtotime("last sunday", strtotime($year_end));
        }
        $num = intval(date('W', $endday));
        for ($i = 1; $i <= $num; $i++) {
            $j = $i - 1;
            $start_date = date("Y-m-d", strtotime("$year_mondy $j week "));
            $end_day = date("Y-m-d", strtotime("$start_date +6 day"));
            $week_array[$i] =
                str_replace("-",
                    "-",
                    $start_date
                ) . '~' .
                str_replace("-", "-", $end_day);
        }
        return $week_array;
    }

    //预约详情组装
    public static function businessDepartDetails($list)
    {
        $tmp = [];
        foreach ($list as $v) {
            $k = $v['dependence_name'];
            $total_status = 0;//该key下评价的总人数
            $wait_time = 0;
            $deal_time = 0;
            if (array_key_exists($k, $tmp)) break;
            //需要把该$k下面所有的数据取出来
            foreach ($list as $v1) {
                if ($v1['dependence_name'] = $k) {//如果相等，则进来
                    $total_status += $v1['total'];
                    $wait_time += $v1['wait_time'];
                    $deal_time += $v1['deal_time'];
                }
            }
            //组装完毕,保存到tmp中以key为键的children中
            $tmp[$k]['total'] = $total_status;
            $tmp[$k]['wait_time'] = $wait_time;
            $tmp[$k]['deal_time'] = $deal_time;
        }

        return $tmp;
    }
}