<?php
/**
 * User: yluchao
 * Date: 2017/7/28
 * Time: 15:01
 */

namespace apps\system\controllers;

use core\services\UserAuth;
use Yii;
use core\controllers\ManagerController;
use yii\db\Exception;

class UserConfigController extends ManagerController
{
    /**
     * 判断用户是否有管理信息控件的权限
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $role = Yii::$app->user->role;
            if ($role == 'root') return true;
            $auths = UserAuth::getAuth(Yii::$app->user->uid);
            if (empty($auths['user'])) {
                Yii::$app->message->fail('您没有管理信息控件的权限');
                Yii::$app->end();
            }
            return true;
        }
    }

    /**
     * 返回用户可见范围的信息控件列表
     * $identity 用户身份列表
     * $list 从配置中读取控件列表
     * $config 从数据表中读取用户设置的信息控件
     */
    public function actionList()
    {
        $config = Yii::$app->dbcache->get('user_config');
        $list = Yii::$app->config->item('user-config.widget');
        $identity = Yii::$app->config->item('user-config.identity');
        foreach ($list as $k => $v) {
            $tmp['name'] = $v;
            foreach($config[$k] as $idx => $val){
                $con_tmp[$idx]['id'] = $val;
                $con_tmp[$idx]['name'] = $identity[$val];
            }
            $tmp['identity'] = $con_tmp;
            $list[$k] = $tmp;
        }
        Yii::$app->message->success($list);
    }

    /**
     * 保存信息控件
     * 参数的值必须为数组:[用户身份id]
     * stuInfo 信息控件-学生信息
     * studyResume 学习简历
     * teaInfo 员工信息
     * workResume 工作简历
     * contact 联系信息
     */
    public function actionSave()
    {
        $stuInfo = Yii::$app->request->post('stuInfo','');
        $studyResume = Yii::$app->request->post('studyResume','');
        $teaInfo = Yii::$app->request->post('teaInfo','');
        $workResume = Yii::$app->request->post('workResume','');
        $contact = Yii::$app->request->post('contact','');

        if(!($stuInfo && $studyResume && $teaInfo && $workResume && $contact)) Yii::$app->message->fail('参数错误');
        $data = [
            'stuInfo' => $stuInfo,
            'studyResume' => $studyResume,
            'teaInfo' => $teaInfo,
            'workResume' => $workResume,
            'contact' => $contact
        ];

        $rs = Yii::$app->dbcache->set('user_config', $data);
        if ($rs)
            Yii::$app->message->success();
        else
            Yii::$app->message->fail();
    }
}