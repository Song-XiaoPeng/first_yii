<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/8/2
 * Time: 14:59
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        // 调用authManager组件
        $auth = Yii::$app->authManager;
        // 添加 "/blog/index" 权限
        $blogIndex = $auth->createPermission('/blog/index');//创建一个permission对象，继承于item 权限名：/blog/index
        $blogIndex->description = '博客列表';//角色描述
        $auth->add($blogIndex);

        // 创建一个角色 '博客管理'，并为该角色分配"/blog/index"权限
        $blogManage = $auth->createRole('博客管理');//创建一个角色 new Role(),role->name=$name;
        $auth->add($blogManage);
        $auth->addChild($blogManage, $blogIndex);
        // 为用户 test1（该用户的uid=1） 分配角色 "博客管理" 权限
        $auth->assign($blogManage, 1); // 1是test1用户的uid
    }

    public function actionInit2()
    {
        $auth = Yii::$app->authManager;

        // 添加权限, 注意斜杠不要反了
        $blogView = $auth->createPermission('/blog/view');
        $auth->add($blogView);
        $blogCreate = $auth->createPermission('/blog/create');
        $auth->add($blogCreate);
        $blogUpdate = $auth->createPermission('/blog/update');
        $auth->add($blogUpdate);
        $blogDelete = $auth->createPermission('/blog/delete');
        $auth->add($blogDelete);

        // 分配给我们已经添加过的"博客管理"权限
        $blogManage = $auth->getRole('博客管理');
        $auth->addChild($blogManage, $blogView);
        $auth->addChild($blogManage, $blogCreate);
        $auth->addChild($blogManage, $blogUpdate);
        $auth->addChild($blogManage, $blogDelete);
    }
}