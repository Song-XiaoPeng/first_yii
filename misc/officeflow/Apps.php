<?php
/**
 * Created by PhpStorm.
 * User: yluchao
 * Date: 2017/7/10
 * Time: 15:01
 */

namespace core\services;


use core\models\AppsModel;
use core\models\AttachementModel;
use core\models\DepartmentModel;
use core\models\TagsModel;
use SimpleWechat\corp\tag\Tag;
use Yii;

class Apps
{

    /**
     * 根据应用标签查询分类中的应用
     * @param $tagid
     * @return mixed
     */
    public static function listsByTagid($tagid)
    {
        $where = [
            'and',
            ['@>', 'tag', '{'.$tagid.'}'],
            AppsModel::duringNow('during'),
            ['visible' => 'yes']
        ];
        $apps = AppsModel::lists($where)['data'];
        $apps = array_map(function ($v) {
            $v['logo'] = \Misc::get('common.schema').\Misc::get('common.imghost').'/'.$v['logo'];
            return $v;
        }, $apps);
        return $apps;
    }


    /**
     * 为了将logo图片封装进返回数据所写的方法，传参及使用方法跟Postgres里面lists方法相同
     * @param $where
     * @param int $offset
     * @param int $limit
     * @param null $order
     * @param string $fields
     * @return array
     */
    public static function lists($where, $offset = 0, $limit = 0, $order = null, $fields = '*')
    {
        $lists = AppsModel::lists($where, $offset, $limit, $order, $fields);
        if ($fields == '*' || $fields == 'logo' || in_array('logo', $fields)) {
            $lists['data'] = array_map(function ($v) {
                $attachement = AttachementModel::one(['id' => $v['logo']]);
                $v['logo_url'] = empty($attachement) ? '' : \Misc::get('common.schema').\Misc::get('common.imghost').'/'.$attachement['file'];
                return $v;
            }, $lists['data']);
        }
        return $lists;
    }

    public static function listsDetail($where, $offset = 0, $limit = 0, $order = null, $fields = '*')
    {
        $list = AppsModel::lists($where, $offset, $limit, $order, $fields);

        $data = array_map(function($val){
            //标签
            $val['tag'] = static::getTags($val['tag']);
            //人群
            $val['usable'] = static::getUsable($val['usable']);
            //时间
            $val['during'] = static::getDateTime($val['during']);
            //图片
            $val['url'] = static::getLogoUrl($val['logo']);
            return $val;
        },$list['data']);
        $list['data'] = $data;
        return $list;
    }

    /**
     * 获得应用的使用时间范围
     * @param $during
     * @return mixed
     */
    public static function getDateTime($during)
    {
        /*$sql = "select upper(during),lower(during) from apps";
        $range = Yii::$app->db->createCommand($sql)->execute();
        $range = explode(',',$range);
        $range = array_map(function($v){
            return explode(' ',$v);
        },$range);*/
        $during = substr($during,2,-2);
        $range = explode('","',$during);
        $range = array_map(function($v){
            return explode(' ',$v);
        },$range);
        $data['date_start'] = empty($range[0][0])?'':$range[0][0];
        $data['date_end'] = empty($range[1][0])?'':$range[1][0];
        $data['time_start'] = empty($range[0][1])?'':$range[0][1];
        $data['time_end'] = empty($range[1][1])?'':$range[1][1];
        return $data;
    }

    /**
     * 根据应用的图片id获得图片的url
     * @param $logo
     * @return mixed
     */
    public static function getLogoUrl($logo)
    {
        return empty($logo)?$logo:AttachementModel::one(['id'=>$logo])['file'];;
    }

    /**
     * 获得所有用户身份
     * @param $usable
     * @return array
     */
    public static function getUsable($usable)
    {
        $identities = Yii::$app->config->item('user-config.identity');
        return array_map(function($v) use($identities){
            return $identities[$v];
        },$usable);
    }

    /**
     * 根据应用的标签id获得标签名
     * @param $tag
     * @return mixed
     */
    public static function getTags($tag)
    {
        return TagsModel::lists(['in','id',$tag],'','','','name')['data'];
    }

    //调用事项模板接口
    public static function listsTemplate($type,$id)
    {
        $where = [
            'and',
            ['@>', $type, '{'.$id.'}'],
            ['visible' => 'yes']
        ];
        return Apps::lists($where,0,0,'',['id','name'])['data'];
    }
    /**
     * 按服务部门分类
     */
    public static function listsByDepartment()
    {
        $lists = DepartmentModel::lists([],0,0,['sort ASC'],['id','name','pid'])['data'];
        if(!$lists) return false;
        return DepartmentModel::getNested(self::getAppsTemplate($lists,'depart',['id','name','depart']),0);
/*        //1.获得所有服务部门
        $lists = DepartmentModel::lists([],0,0,['sort ASC'])['data'];
        //2.获得所有应用
        $where = [
            'and',
            AppsModel::duringNow('during'),
            ['visible' => 'yes']
        ];
        $apps = AppsModel::lists($where,0,0,'',['id','name','usable'])['data'];

        array_walk($lists,function(&$v) use($apps){
            foreach($apps as $v1){
                if(in_array($v['id'],$v1['departs'])){
                    $v['apps'][] = $v1;
                }
            }
        });
        return DepartmentModel::getNested($lists,0);*/
    }

    /**
     * 按服务对象分类
     */
    public static function listsByIdentities()
    {
        $identities = array_flip(Yii::$app->config->item('user-config.identity'));
        if(!$identities) return false;
        $identities = array_map(function($v){
            $a['id'] = $v;
            return $a;
        },$identities);
        return self::getAppsTemplate($identities,'identity',['id','name','identity']);
        /*//获得所有服务对象
        $identities = array_flip(Yii::$app->config->item('user-config.identity'));
        $identities = array_map(function($v){
            $a['id'] = $v;
            return $a;
        },$identities);
        //2.获得所有应用
        $where = [
            'and',
            AppsModel::duringNow('during'),
            ['visible' => 'yes']
        ];
        $apps = AppsModel::lists($where,0,0,'',['id','name','usable'])['data'];
        array_walk($identities,function(&$v) use($apps){
            foreach($apps as $v1){
                if(in_array($v['id'],$v1['usable'])){
                    $v['apps'][] = $v1;
                }
            }
        });
        return $identities;*/
    }

    /**
     * 事项模板
     */
    public static function getAppsTemplate($lists,$k,$fields)
    {
        $apps = self::lists(['visible' => 'yes'],0,0,'',$fields)['data'];
        if(!$apps) return false;
        array_walk($lists,function(&$v) use($apps,$k){
            foreach($apps as $v1){
                if(in_array($v['id'],$v1[$k])){
                    $v['apps'][] = $v1;
                }
            }
        });
        return $lists;
    }
}