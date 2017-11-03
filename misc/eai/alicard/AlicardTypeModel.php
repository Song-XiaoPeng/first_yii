<?php

namespace apps\alicard\models;

use apps\alicard\ar\AlicardType;
use apps\alicard\ar\AlicardTypeOrganize;
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
                    $name[$v1['organize_id']] = $organize[$v1['organize_id']];
                }
            }
            if (!empty($type[$v['type_id']])) {
                $type[$v['type_id']]['range'] = implode('、', $name);
                $type[$v['type_id']]['organize_ids'] = array_keys($name);
            } else {
                $type[$v['type_id']]['range'] = '';
                $type[$v['type_id']]['organize_ids'] = [];
            }
        }
        return $type;
    }

    /*
     *return [
     *     [
     *      "organize_id"=>119,
            "organize_name"=>"长江云平台"
            ]
     *  ]
     * */
    public static function id2Organize($id)
    {
        $organize_ids = AlicardTypeOrganize::find()->where(['type_id' => $id])->asArray()->all();
        //获得组织架构（树级）
        $organize = UserModel::roles('department');
        //将树级组织机构转为普通结构
        $organize = self::roleList($organize);

        $data = [];

        foreach ($organize_ids as $k => $v) {
            $data[$k]['organize_id'] = $v['organize_id'];
            $data[$k]['organize_name'] = $organize[$v['organize_id']];
        }

        return $data;
    }

    /**
     * 删除支付宝对应卡类型的卡
     */
    public static function delAlicard($set)
    {
        if ($set) {
            foreach ($set as $card_no) {
                $rs = Yii::$app->aop->call('alipay.marketing.card.delete', [
                    'out_serial_no' => strval(time()),
                    'target_card_no' => $card_no,
                    'target_card_no_type' => 'BIZ_CARD',
                    'reason_code' => 'USER_UNBUND'
                ]);
                $response = $rs->alipay_marketing_card_delete_response;
                if ($response->code !== '10000') {
                    error_log(var_export($response, true), 3, '/tmp/alicard_error');
                }
            }
        }
    }

    /**
     * 更新支付宝对应卡类型的卡
     */
    public static function updateAlicard($set)
    {
        foreach ($set['card'] as $v) {

        }

        $rs = Yii::$app->aop->call('alipay.marketing.card.update', [
            'target_card_no' => $exists['biz_card_no'],
            'target_card_no_type' => 'BIZ_CARD',
            'occur_time' => date('Y-m-d H:i:s'),
            'card_info' => [
                'template_id' => $template_id,
                'open_date' => date('Y-m-d H:i:s', $exists['created']),
                'valid_date' => $data['valid_date'],
                'front_text_list' => $info,
                'front_image_id' => $photo,
            ]
        ]);

        $response = $rs->alipay_marketing_card_update_response;
        if ($response->code !== "10000") {
            Yii::$app->message->fail('请求支付宝接口失败 ' . $response->msg . ' ' . $response->sub_msg);
        }
    }

    /**
     * @param $data
     */
    public static function getColumns($data)
    {

    }
}