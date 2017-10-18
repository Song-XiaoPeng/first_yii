<?php

namespace apps\alicard\models;

use apps\alicard\ar\AlicardTypeOrganize;
use portal\helpers\CacheKey;
use portal\helpers\ErrorMessage;
use portal\models\LogModel;
use portal\models\UserModel;
use Yii;

class AlicardTypeModel
{
    //获得组织架构列表（树形结构转为普通结构）
    public static function roleList($list)
    {
        static $tmp = [];
        foreach ($list as $v) {
            $tmp[$v['id']] = $v['name'];
            if (!empty($v['children'])) {
                self::roleList($v['children']);
            }
        }
        return $tmp;
    }

    //获得阿里校园卡的详细列表
    public static function typeList($list)
    {
        $type = array_column($list, null, 'id');
        $type_ids = array_column($type, 'id');

        //查找校园卡 组织架构关联表
        $organize_type = AlicardTypeOrganize::find()->where(['type_id' => $type_ids])->asArray()->all();

        //获得组织架构（树级）
        $organize = UserModel::roles('department');

        //将树级组织机构转为普通结构
        $organize = self::roleList($organize);
        
        $tmp = [];
        foreach ($organize_type as $k => $v) {
            if (in_array($v['type_id'], $tmp)) continue;
            array_push($tmp, $v['type_id']);
            $name = [];
            foreach ($organize_type as $k1 => $v1) {
                if ($v1['type_id'] == $v['type_id']) {
                    $name[] = $organize[$v1['organize_id']];
                }
            }
            !empty($type[$v['type_id']]) && $type[$v['type_id']]['range'] = implode('、', $name);
        }
        return $type;
    }
}