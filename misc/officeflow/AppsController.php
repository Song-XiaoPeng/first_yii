<?php
/**
 * Created by PhpStorm.
 * User: yluchao
 * Date: 2017/7/10
 * Time: 14:52
 */

namespace apps\api\controllers;

use core\models\AppsModel;
use core\models\DepartmentModel;
use core\models\TagsModel;
use core\services\Tags;
use SimpleWechat\corp\department\Department;
use Yii;
use core\controllers\ApiController as BaseController;
use core\services\Apps;
use yii\db\Exception;

class AppsController extends BaseController
{
    /**
     * 获取全部应用  根据标签类型进行返回
     *
     * [
     *      '10' =>
     *          [
     *              'name' => '后勤集团',
     *              'type' => '生活服务',
     *              'apps' =>[
     *                  0 => [
     *                      'name' => '水费充值'
     *                      ···
     *                  ]
     *              ]
     *          ]
     * ]
     */
    public function actionIndex()
    {
        $tagid = Yii::$app->request->get('tagid', 0);
        $lists = Apps::lists($tagid);
        if ($tagid)
            Yii::$app->message->success(current($lists));
        else
            Yii::$app->message->fail('', $lists);
    }

    public function actionSave()
    {
        $data['name'] = Yii::$app->requset->post('name', '');
        $data['version'] = Yii::$app->requset->post('version', '');
        $data['detail'] = Yii::$app->requset->post('detail', '');
        $data['visible'] = Yii::$app->requset->post('visible', '');
        $data['tag'] = Yii::$app->requset->post('tag', []);
        $usable = Yii::$app->requset->post('usable', []);
        $data['usable'] = json_encode($usable, JSON_UNESCAPED_UNICODE);
        $start = Yii::$app->request->post('start', '');
        $end = Yii::$app->request->post('end', '');
        $data['during'] = "[{$start},{$end})";
        $id = Yii::$app->request->post('id', '');
        !empty($id) && $data['id'] = $id;
        $rs = AppsModel::save($data);
        if ($rs)
            Yii::$app->message->success();
        else
            Yii::$app->message->fail();
    }

    /**
     * 删除应用 根据id 删除
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id', 0);
        if (empty($id)) Yii::$app->message->fail('参数错误');
        $info = AppsModel::one(['id' => $id]);
        if (empty($info)) Yii::$app->message->fail('应用不存在');
        $rs = AppsModel::delete(['id' => $id]);
        if ($rs)
            Yii::$app->message->success();
        else
            Yii::$app->message->fail();
    }

    /**
     * 事项模版接口
     * @$type tag|depart|identity
     */
    public function actionAppTemplates()
    {
        $type = Yii::$app->request->get('type');
        if(empty($type)) Yii::$app->message->fail('参数错误');
        $id = intval(Yii::$app->request->get('id'));
        if (empty($id)) Yii::$app->message->fail('参数错误');

        $rs = Apps::listsTemplate($type,$id);
        if ($rs)
            Yii::$app->message->success($rs);
        else
            Yii::$app->message->fail();
    }
    
    /**
     * @return array
     * 服务部门接口
     */
    public function actionDepartments()
    {
        $lists = DepartmentModel::lists([],0,0,['sort ASC'])['data'];
        return DepartmentModel::getNested($lists,0);
    }

    /**
     * 服务对象接口
     */
    public function actionIdentities()
    {
        $rs = Yii::$app->config->item('user-config.identity');
        if ($rs)
            Yii::$app->message->success($rs);
        else
            Yii::$app->message->fail();
    }

    /**
     * 事项模版接口
     */
    public function actionApps()
    {
        try{
            //按服务分类
            $tags = Apps::listsByTag();
            if(!$tags) Yii::$app->message->fail();
            //按服务对象
            $identities = Apps::listsByIdentities();
            if(!$identities) Yii::$app->message->fail();
            //按服务部门
            $departments = Apps::listsByDepartment();
            if(!$departments) Yii::$app->message->fail();
            $data['tags'] = $tags;
            $data['identities'] = $identities;
            $data['departments'] = $departments;
            Yii::$app->message->success($data);
        }catch(Exception $e){
            Yii::$app->message->fail();
        }
    }

    /**
     * 添加事项/保存事项接口
     */
    public function actionAdd()
    {
        $id = intval(Yii::$app->request->post('id',''));
        $data['name'] = Yii::$app->request->post('name','');
        $data['logo'] = Yii::$app->request->post('logo','');
        if (empty($data['name']) || empty($data['logo'])) Yii::$app->message->fail('参数错误');

        $id && $data['id'] = $id;

        //id存在则为修改，id不存在则为添加
        $rs = TagsModel::save($data);
        if ($rs)
            Yii::$app->message->success();
        else
            Yii::$app->message->fail();
    }

    /**
     * 标签分类接口
     */
    public function actionTags()
    {
        $rs = TagsModel::lists(['is_publish'=>'yes'],0,0,['sort ASC'])['data'];

        if ($rs){
            $rs = array_map(function($v){
                $v['logo'] = \Misc::get('common.schema').\Misc::get('common.imghost').'/'.$v['logo'];
                return $v;
            },$rs);

            Yii::$app->message->success($rs);
        }else {
            Yii::$app->message->fail();
        }
    }

    /**
     * 发布/取消发布事项接口
     */
    public function actionPublish()
    {
        $id = intval(Yii::$app->request->post('id',''));
        $is_publish = Yii::$app->request->post('is_publish','');
        if(empty($id) || empty($is_publish)) Yii::$app->message->fail('参数错误');

        $rs = TagsModel::update(['is_publish'=>$is_publish],['id'=>$id]);
        if ($rs)
            Yii::$app->message->success();
        else
            Yii::$app->message->fail();
    }


}