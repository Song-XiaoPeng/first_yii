<?php
namespace apps\system\controllers;

use apps\system\models\TeamModel;
use core\controllers\ManagerController;
use core\models\AppSpecialModel;
use core\services\AppProcess;
use core\services\AppSpecial;
use Yii;
use yii\db\Exception;

class AppSpecialController extends ManagerController
{
    private $_typeHot = 0;
    private $_typeWant = 1;
    public function actionIndex()
    {
        $where = [];
        $kw = get('kw');
        $type = Yii::$app->request->get('type',0);
        if ($kw) {
            $where = ['LIKE', 'name', $kw];
        }
        $p = Yii::$app->page;

        list($offset, $limit) = $p->getLimit();

        $list = AppSpecial::getAppsByKw($type,$where,$offset,$limit);
        $p -> setTotal($list['total']);
        $list['data'] = array_map(function($v) {
            $v['during_detail'] = AppProcess::getDateTime($v['during']);
            $v['usable'] = AppProcess::getUsable($v['usable']);
            return $v;
        },$list['data']);

        return $this->render('index',[
            'list'=>$list['data'],
            'type' => $type,
            'kw' =>$kw,
        ]);
    }

    /**
     * 设置猜你想用
     */
    public function actionSaveWantApps()
    {
        $want_apps = Yii::$app->request->post('want_apps','');
        $show_want = Yii::$app->request->post('show_want','');
        $identity = intval( Yii::$app->request->post('identity','') );

        if( !(empty($want_apps) && empty($show_want)) ) Yii::$app->message->fail("保存数据不能为空");

        $tran = Yii::$app->db->beginTransaction();
        try{

            $rs = Yii::$app->dbcache->set('apps_want_is_show', $show_want);

            if(empty($rs)) throw new Exception('设置是否显示猜你想用模块失败');

            array_walk($want_apps, function(&$v) use(&$identity){
                $id = isset($v['id']) ? intval($v['id']) : 0;
                $data['type'] = $this->_typeWant;
                $data['identity'] = $identity;
                $data['app_id'] = isset($v['app_id']) ? intval($v['app_id']) : 0;
                $data['sort'] = isset($v['sort']) ? intval($v['sort']) : 0;
                $data['is_show'] = isset($v['is_show']) ? $v['is_show'] : 'no';

                if(empty($data['app_id'])) throw new Exception('请选择应用');
                if( AppSpecialModel::exist($id, $data['app_id'], $data['type'], $data['identity']) ) throw new Exception('猜你想用应用已存在,请重新选择');
                if( !(is_int($data['sort']) && $data['sort'] >= 0 && $data['sort'] <= 10000) ) throw new Exception('排序数值范围必须为0-10000的整数');

                !empty($id) && $data['id'] = $id;
                $app_special_id = AppSpecialModel::save($data);
                if(empty($app_special_id)) throw new Exception('应用保存失败');
            });

            $tran->commit();
            Yii::$app->message->success('保存成功');
        }catch(Exception $e){
            $tran->rollBack();
            Yii::$app->message->fail($e->getMessage());
        }

        /*if(empty($post['ids'])){
            Yii::$app->message->fail('请选择应用');
        }
        $type = $post['type'];
        //根据$type查找数据库中所有对应类型的app-id
        $exists_ids = AppSpecialModel::findIdByType($type);
        $data = array_diff($post['ids'],$exists_ids);//返回在arr1中不在arr2中的值

        if(empty($data)) Yii::$app->message->success('');
        $data = array_map(function($v) use($type){
            return ['app_id'=>$v,'type'=>$type];
        },$data);

        $rs = AppSpecialModel::batchInsert($data);

        if($rs){
            Yii::$app->message->success();
        }else{
            Yii::$app->message->fail();
        }*/
    }

    public function actionSaveHotApps()
    {
        
    }
    
    public function actionDelete()
    {
        $post= Yii::$app->request->post();
        if(!is_array($post['ids'])) $post['ids']=(array)$post['ids'];

        if(empty($post['ids'])){
            Yii::$app->message->fail('请选择应用');
        }
        $rs = AppSpecialModel::delete(['and',['in','app_id',$post['ids']],['type'=>$post['type']]]);

        if($rs){
            Yii::$app->message->success();
        }else{
            Yii::$app->message->fail();
        }
    }
}