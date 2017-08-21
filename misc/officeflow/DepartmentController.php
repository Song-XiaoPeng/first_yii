<?php
namespace apps\system\controllers;
use core\controllers\ManagerController;
use core\models\DepartJobModel;
use core\models\DepartmentModel;
use core\models\JobModel;
use core\models\RelationModel;
use core\services\Department;
use core\services\UserAuth;
use core\services\Users;
use Yii;
class DepartmentController extends ManagerController
{
    private $_type = "department";

    /**
     * 判断用户是否有管理部门的权限
     */
    public function beforeAction($action)
    {
        if (YII_DEBUG)return true;
        if (in_array($action->id,['did-to-info']))return true;
        if (parent::beforeAction($action)) {
            return parent::validUserAuth('department', '抱歉，你没有部门管理权限');
        }
    }

    # 获取部门
    public function actionList()
    {
        $data = DepartmentModel::lists([], '', '', ['id asc']);
        $tree = genTree($data['data']);
        $tree = $this->addChildren($tree);
        $data = [
            "id" => 0,
            "pid" => 0,
            "name" => "根部门",
            "children" => $tree
        ];
        Yii::$app->message->success($data);
    }

    # 添加children
    private function addChildren(&$tree)
    {
        foreach ($tree as $k => &$v){
            if (!empty($v['children']) && is_array($v['children'])){
                $this->addChildren($v['children']);
            }
            if (!isset($v['children'])){
                $v['children'] = [];
            }
        }
        return $tree;
    }

    # 保存数据 | 编辑
    public function actionSave()
    {
        if (!Yii::$app->request->isPost)Yii::$app->message->fail("请使用正确的请求方式POST");
        $post = Yii::$app->request->post();
        $data['pid'] = isset($post['pid']) ? $post['pid'] : 0;
        $data['name'] = isset($post['name']) ? $post['name'] : '';
        $data['creator'] = isset(Yii::$app->user->uid) ? Yii::$app->user->uid:0;
        $data['sort'] = isset($post['sort']) ? $post['sort'] : 0;
        $data['type'] = $this->_type;
        $data['sn'] = !empty($post['sn']) ? $post['sn'] : '';

        if (empty($data['name']))Yii::$app->message->fail("部门名称不能为空");
        $job = isset($post['job']) ? $post['job'] : [];
        $relation = isset($post['relation']) ? $post['relation'] : [];

        isset($post['id']) && $data['id'] = $post['id'];

        if (empty($data['name']))Yii::$app->message->fail("部门名称不能为空");

        $trans = Yii::$app->db->beginTransaction();
        try{

            # 保存部门信息
            $res = DepartmentModel::save($data);
            if ((!isset($post['id']) || !empty($post['id'])) && $res)$post['id'] = $res;

            if (empty($post['id'])) throw new \Exception("部门id不存在");

            # 相对角色成员
            if (!empty($relation) && !Department::saveRelation($post)) throw new \Exception("相对角色保存失败");

            # 保存岗位
            if (!empty($job) && !Department::saveDepartJob($post)) throw new \Exception("岗位保存失败");

            $trans->commit();
            Yii::$app->message->success();
        }catch (\Exception $e){
            $trans->rollBack();
            Yii::$app->message->fail($e->getMessage());
        }
    }

    # 删除部门
    public function actionDelete()
    {
        if (!Yii::$app->request->isPost)Yii::$app->message->fail("请使用正确的请求方式POST");
        $id = intval(Yii::$app->request->post('departid',0));
        empty($id) && Yii::$app->message->fail("请求参数不能为空");

        DepartmentModel::isExistUser($id) && Yii::$app->message->fail("请先删除不能下的用户");

        try {
            $res = DepartmentModel::delete(['id' => $id]);
            $res ? Yii::$app->message->success() : Yii::$app->message->fail();
        } catch (\Exception $e) {
            Yii::$app->message->fail($e->getMessage());
        }
    }

    # 根据部门id 获取部门信息 包括基本信息和部门管理信息
    public function actionDidToInfo()
    {
        $departId = Yii::$app->request->get('departid');

        if (empty($departId))Yii::$app->message->fail("部门id不能为空");

        # 基础信息
        $base = DepartmentModel::one(['id' => $departId]);
        if (empty($base))Yii::$app->message->fail("编辑的部门不存在");
        # 上级部门
        if ($base['pid'] == 0) {
            $base['pname'] = $base['name'];
        } else {
            $parent = DepartmentModel::one(['id' => $base['pid']]);
            $base['pname'] = isset($parent['name']) ? $parent['name'] : '';
        }

        # 相对角色
        $relationData = $auths = Yii::$app->config->item('department.relation');
        $relation = RelationModel::lists(['departid' => $departId])['data'];
        if (!empty($relation)) {
            foreach ($relation as &$v){
                $v['relation_name'] = isset($relationData[$v['relation_id']]) ? $relationData[$v['relation_id']] : '';
                if (isset($v['member']) && !empty($v['member'])) {
                    $userArr = [];
                    array_walk($v['member'],function(&$value) use(&$userArr){
                        $userInfo = Users::uid2info($value);
                        $userArr = [
                            'uid' => $value,
                            'username' => isset($userInfo['username']) ? $userInfo['username'] : ''
                        ];
                    });
                    $v['member'] = $userArr;
                }
            }
            #排序
            $relation = self::arraySort($relation,'relation_id','asc');
        }

        # 岗位
        $job = DepartJobModel::lists(['departid' => $departId])['data'];
        if (!empty($job)) {
            foreach ($job as &$v){
                $jobInfo = JobModel::one(['id' => $v['jobid']]);
                $v['jobname'] = isset($jobInfo['name']) ? $jobInfo['name'] : '';
            }
        }

        $base['relation'] = $relation;
        $base['job'] = $job;

        Yii::$app->message->success($base);
        
    }

    # 数组排序
    public static function arraySort($arr,$keys,$order = 'asc')
    {
        $keysArr = $formatArr = [];
        array_walk($arr, function($v , $k)use($keys,&$keysArr) {
            $keysArr[$k] = $v[$keys];
        });
        $order == 'asc' ? asort($keysArr) : arsort($keysArr);
        reset($keysArr);
        array_walk($keysArr, function($value , $key) use(&$formatArr , $arr) {
            $formatArr[] = $arr[$key];
        });
        return $formatArr;
    }
}