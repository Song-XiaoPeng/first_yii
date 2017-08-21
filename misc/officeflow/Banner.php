<?php
namespace core\services;
use core\helpers\ErrorMessage;
use core\models\AttachementModel;
use core\models\BannerModel;
use Yii;
class Banner
{

    public static function getList()
    {
        $list = BannerModel::lists([], '', '', ['sort ASC', 'id ASC'])['data'];
        if(!$list) return [];
        array_walk($list,function(&$v){
            $v['logo_url'] = \Misc::get('common.schema') . \Misc::get('common.imghost') . '/' . $v['path'];
        });
        return $list;
    }

    public static function delete($id)
    {
        $mess = ErrorMessage::ERR_INTERNAL;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $one = BannerModel::one(['id'=>$id]);
            $res = BannerModel::delete(['id'=>$id]);
            if(!$res) $transaction->rollBack();
            $res1 = AttachementModel::delete(['id'=>$one['id']]);
            if(!$res1) $transaction->rollBack();
            $transaction->commit();
            $mess = 0;
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
        return $mess;
    }

    public static function change($id)
    {
        $mess = ErrorMessage::ERR_INTERNAL;
        $one = BannerModel::one(['id'=>$id]);
        if ($one['is_show'] == 0) {
            $res = BannerModel::update(['is_show'=>1],['id'=>$id]);
        } else {
            $res = BannerModel::update(['is_show'=>0],['id'=>$id]);
        }
        $mess = $res ? 0 : $mess;
        return $mess;
    }

    public static function deleteSelect($ids)
    {
        $mess = ErrorMessage::ERR_INTERNAL;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $data = BannerModel::lists(['in','id',$ids]);
            if(!empty($data)) {
                $a_ids = array_column($data,'attachment_id');
                $res1 = BannerModel::delete(['in','id',$ids]);
                if(!$res1) $transaction->rollBack();
                $res2 = AttachementModel::delete(['in','id',$a_ids]);
                if(!$res2) $transaction->rollBack();
                $transaction->commit();
                $mess = 0;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
        return $mess;
    }

    public static function sort($data)
    {
        $mess = ErrorMessage::ERR_INTERNAL;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if (!empty($data)) {
                foreach ($data as $k=>$v) {
                    $val = json_decode($v,true);
                    $banner = BannerModel::one(['id'=>$val['id']]);
                    if ($banner) {
                        BannerModel::save(['id'=>$val['id'],'sort'=>$val['sort']]);
                    } else {
                        throw new \Exception('');
                    }
                }
                $transaction->commit();
            }
            $mess = 0;
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
        return $mess;
    }
}