<?php
/**
 * User: dell
 * Date: 2017/7/10
 * Time: 13:36
 */

namespace apps\api\controllers;

use core\models\AppsModel;
use core\services\Tags;
use Yii;
use core\controllers\ApiController as BaseController;
use core\models\TagsModel;
use yii\db\Exception;

/**
 * Class TagsController 标签
 * @package apps\api\controllers
 */
class TagsController extends BaseController
{
    /**
     * 获取所有的标签
     */
    public function actionIndex()
    {
        $lists = TagsModel::lists([]);
        Yii::$app->message->success($lists);
    }

    /**
     *删除分类，如果分类下面有应用,不让删除
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id', 0);
        if (empty($id)) Yii::$app->message->fail('参数错误');
        $info = TagsModel::one(['id' => $id]);
        if (empty($info)) Yii::$app->message->fail('标签不存在');
        $apps = AppsModel::one(['@>', 'tag', '{'.$id.'}']); // @TODO 待验证这种方法是否可用
        if (!empty($apps)) Yii::$app->message->fail('不允许删除存在应用的标签');
        $rs = TagsModel::delete(['id' => $id]);
        if ($rs)
            Yii::$app->message->success();
        else
            Yii::$app->message->fail();
    }

    /**
     * 发布/取消发布分类接口
     */
    public function actionPublish()
    {
        $id = intval(Yii::$app->request->post('id', 0));
        $is_publish = Yii::$app->request->post('is_publish' );
        if(empty($id) || $is_publish !=='yes' && $is_publish !== 'no') Yii::$app->message->fail('参数错误');
        $rs = TagsModel::update(['is_publish'=>$is_publish],['id'=>$id]);
        if ($rs) {
            $tag = Tags::one(['id' => $id]);
            $tag && Yii::$app->message->success($tag);
            Yii::$app->message->fail();
        }else {
            Yii::$app->message->fail();
        }
    }

    /**
     * 保存分类接口
     */
    public function actionSave()
    {
        $id = intval(Yii::$app->request->post('id', 0));
        $data['name'] = Yii::$app->request->post('name' );
        $data['logo'] = Yii::$app->request->post('logo' );
        if(!$data['name'] || !$data['logo']) Yii::$app->message->fail('参数错误');

        if($id){
            $data['id'] = $id;
            $rs = TagsModel::update($data,['id'=>$id]);
        }else{
            $tran = Yii::$app->db->beginTransaction();
            try{
                $rs = TagsModel::insert($data);
                $rs1 = TagsModel::update(['sort'=>$rs],['id'=>$rs]);
                if(!($rs && $rs1 && $rs === $rs1)) throw new \Exception('保存失败');
                $tran->commit();
            }catch(Exception $e){
                $tran->rollBack();
                Yii::$app->message->fail($e->getMessage());
            }
        }
        if (!empty($rs)) {
            $tag = Tags::one(['id' => $rs]);
            !empty($tag) && Yii::$app->message->success($tag);
            Yii::$app->message->fail();
        }else {
            Yii::$app->message->fail();
        }
    }

    /**
     * 添加编辑分类接口
     */
    public function actionAdd()
    {
        $id = intval(Yii::$app->request->get('id', 0));
        if($id) {
            $rs = Tags::one(['id'=>$id]);
            if ($rs)
                Yii::$app->message->success($rs);
            else
                Yii::$app->message->fail();
        }else{
            Yii::$app->message->success();
        }
    }

    /**
     * 排序接口
     */
    public function actionSort()
    {
        $id = intval(Yii::$app->request->post('id', 0));
        $sort = Yii::$app->request->post('sort');//排序方式：1位置后移，-1位置前移
        if(!$id || !in_array($sort,[-1,1])) Yii::$app->message->fail('参数错误');
        $lists = TagsModel::lists(['is_publish'=>'yes'],0,0,['sort ASC'],['id','sort'])['data'];
        foreach($lists as $k=>$v){
            if($v['id'] == $id){
                $tran = Yii::$app->db->beginTransaction();
                try{
                    $idx = $k+$sort;
                    if($idx<0 || $idx>count($lists)-1) throw new \Exception('参数错误');
                    $sort1 = $v['sort'];
                    $sort2 = $lists[$idx]['sort'];
                    $id2 = $lists[$idx]['id'];

                    $rs1 = TagsModel::update(['sort'=>$sort2],['id'=>$id]);
                    $rs2 = TagsModel::update(['sort'=>$sort1],['id'=>$id2]);
                    if(!$rs1 || !$rs2) throw new \Exception('保存失败');
                    $tran->commit();
                }catch(Exception $e){
                    $tran->rollBack();
                    Yii::$app->message->fail($e->getMessage());
                }
            }
            break;
        }

        $lists = TagsModel::lists(['is_publish'=>'yes'],0,0,['sort ASC'])['data'];
        if($lists )
            Yii::$app->message->success($lists);
        else
            Yii::$app->message->fail();
    }
}