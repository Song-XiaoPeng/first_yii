<?php

namespace apps\backend\controllers;

use core\models\BusinessDistributeModel;
use core\models\BusinessModel;
use core\models\CollegeModel;
use core\models\HallModel;
use core\models\ManagerModel;
use core\models\OfficeModel;
use core\services\Business;
use core\services\Manager;
use Yii;
use core\controllers\BackendController;
use yii\db\Exception;

class BusinessController extends BackendController
{
    private $_err = [];

    public function highLightRules()
    {
        return [
            ['actions' => ['business'], 'rule' => 'business'],
            ['actions' => ['business'], 'rule' => 'business.business'],
        ];
    }

    public function accessRules()
    {
        return [
            ['allow', 'actions' => ['business', 'business-save', 'business-del', 'managers', 'get-manager', 'college', 'hall', 'office', 'one'], 'rules' => ['business']],
        ];
    }

    ###=====================================================================================
    ### 业务管理
    ###=====================================================================================
    public function actionBusiness()
    {
        $se_name = Yii::$app->request->get('name', '');
        $se_type = Yii::$app->request->get('type', '');
        $start = Yii::$app->request->get('start', '');
        $end = Yii::$app->request->get('end', '');
        $where = [];
        $order = ['id ASC'];

        if ($se_name) {
            $business_id = Business::name2Ids($se_name);
            $where[] = ['id' => $business_id];
        }

        if ($se_type) {
            $where[] = ['type' => $se_type];
        }

        if ($start && $end) {
            $where[] = [
                'and',
                ['>=', 'createtime', strtotime($start)],
                ['<=', 'createtime', strtotime($end) + 24 * 3600]
            ];
            $order = ['createtime ASC'];
        } elseif ($start) {
            $where[] = [
                'and',
                ['>=', 'createtime', strtotime($start)],
                ['<=', 'createtime', strtotime($start) + 24 * 3600]
            ];
            $order = ['createtime ASC'];
        } elseif ($end) {
            $where[] = [
                'and',
                ['>=', 'createtime', strtotime($end)],
                ['<=', 'createtime', strtotime($end) + 24 * 3600]
            ];
            $order = ['createtime ASC'];
        }

        if (count($where) > 1) {
            array_unshift($where, 'AND');
        } else {
            $where = array_shift($where);
        }

        $limit = Yii::$app->page->getLimit();
        $rs = Business::notDeletedlists($where, $limit, $order);

        Yii::$app->page->setTotal($rs['count']);

        $office = OfficeModel::lists(['status' => 0], null, null, ['id ASC'], ['id', 'name']);
        $college = CollegeModel::lists(['status' => 0], null, null, ['id ASC'], ['id', 'name']);
        $hall = HallModel::lists(['status' => 0], null, null, ['id ASC'], ['id', 'name']);
        $type = Yii::$app->config->item('params.type');

        foreach ($type as $k => &$v) {
            $tmp['name'] = $v;
            $tmp['id'] = $k;
            $v = $tmp;
        }

        $type = array_column($type, null, 'id');
        $office = array_column($office, null, 'id');
        $college = array_column($college, null, 'id');
        $hall = array_column($hall, null, 'id');

        foreach ($rs['lists'] as &$v) {
            $v['type_name'] = $type[$v['type']]['name'];
            $v['office_name'] = !empty($v['office_id']) && $v['type'] == 1 ? (!empty($office[$v['office_id']]['name']) ? $office[$v['office_id']]['name'] : '') : '';
            $v['college_name'] = !empty($v['college_id']) ? (!empty($college[$v['college_id']]['name']) ? $college[$v['college_id']]['name'] : '') : '';
            $v['hall_name'] = !empty($v['hall_id']) ? (!empty($hall[$v['hall_id']]['name']) ? $hall[$v['hall_id']]['name'] : '') : '';
        }

        return $this->render('business', [
            'data' => $rs['lists'],
            'office' => $office,
            'college' => $college,
            'hall' => $hall,
            'type' => $type,
            'search' => [
                'name' => $se_name,
                'type' => $se_type,
                'start' => $start,
                'end' => $end
            ]
        ]);
    }

    public function actionBusinessSave()
    {
        $tran = Yii::$app->db->beginTransaction();
        try {
            $business_id = Yii::$app->request->post('businessid');
            $data['name'] = Yii::$app->request->post('name');
            $data['require'] = Yii::$app->request->post('require');
            $data['type'] = Yii::$app->request->post('type', 0);
            $data['college_id'] = Yii::$app->request->post('college_id', 0);
            $data['hall_id'] = Yii::$app->request->post('hall_id', 0);
            $data['office_id'] = Yii::$app->request->post('office_id_office', 0);
            $data['createtime'] = time();
            switch ($data['type']) {
                case BusinessModel::IDENTITY_OFFICE:
                    $data['office_id'] = Yii::$app->request->post('office_id_office', 0);
                    break;
                case BusinessModel::IDENTITY_HALL:
                    $data['office_id'] = Yii::$app->request->post('office_id_hall', 0);
                    break;
                default:
                    $data['office_id'] = 0;
                    break;
            }
            if ($business_id) $data['id'] = $business_id;
            $where = [
                'AND',
                ['name' => $data['name']],
                ['=', 'office_id', $data['office_id']],
                ['=', 'college_id', $data['college_id']],
                ['=', 'hall_id', $data['hall_id']],
                ['=', 'type', $data['type']],
                ['!=', 'id', $business_id],
                ['status' => 0]
            ];

            if (empty($data['name'])) throw new Exception('业务名称不能为空，请重新输入');
            if (empty($data['require'])) throw new Exception('业务所需资料不能为空，请重新输入');
            if (empty($data['type'])) throw new Exception('请选择业务类型');

            if ($data['type'] == BusinessModel::IDENTITY_HALL) {
                if (!($data['hall_id'] && $data['office_id'])) throw new Exception('请选择所属部门');
            } else {
                if (!($data['office_id'] || $data['college_id'])) throw new Exception('请选择所属部门');
            }

            if (BusinessModel::one($where, 1)) throw new Exception('该分类下业务名称已存在，请重新输入');

            $rs = BusinessModel::save($data);
            if (!$rs) throw new Exception('业务保存失败');
            $business_id = empty($business_id) ? BusinessModel::insertId() : $business_id;

            //修改业务时，删除关联表里所有原来的数据
//            Yii::$app->message->success($business_id);
//            BusinessDistributeModel::delete(['business_id' => $business_id]);
//            if (!$rs) throw new Exception('原有业务和老师关联数据删除失败');
            $exist = $business_id ? BusinessDistributeModel::one(['business_id' => $business_id]) : null;
            if ($exist) {
                $distribute_rs = BusinessDistributeModel::delete(['business_id' => $business_id]);
                if (!$distribute_rs) throw new Exception('原有业务和老师关联数据删除失败');
            }

            unset($data);
            $manager_ids = Yii::$app->request->post('manager_id');
            if (!empty($manager_ids)) {
                if (count($manager_ids) > 6) throw new Exception('一次操作最多只能添加6位老师，请重新选择');
                foreach ($manager_ids as $v) {
                    $pos = strpos($v, '_');

                    if ($pos !== false) {
                        $data['manager_id'] = substr($v, 0, $pos);
                        $data['window'] = substr($v, $pos + 1);
                    } else {
                        $data['manager_id'] = $v;
                        $data['window'] = '';
                    }
                    $data['business_id'] = $business_id;
                    $rs = BusinessDistributeModel::insert($data, false);
                    if (empty($rs)) array_push($this->_err, $rs);
                }
            }
//            var_dump($data);die;
            if (empty($this->_err)) {
                $tran->commit();
                Yii::$app->message->success();
            } else {
                throw new Exception('保存失败，请重试');
            }
        } catch (\Exception $e) {
            Yii::$app->message->fail($e->getMessage());
            $tran->rollBack();
        }
    }

    public function actionBusinessDel()
    {
        $id = Yii::$app->request->post('id');
        if (!$id) Yii::$app->message->fail('参数错误');
        $tran = Yii::$app->db->beginTransaction();
        try {
            $rs = BusinessModel::update(['status' => 1], ['id' => $id]);
            if (!$rs) throw new Exception('删除业务失败');
            $exist = BusinessDistributeModel::one(['business_id' => $id]);
            if ($exist) {
                $distribute_rs = BusinessDistributeModel::delete(['business_id' => $id]);
                if (!$distribute_rs) throw new Exception('删除业务关联数据失败');
            }
            $tran->commit();
            Yii::$app->message->success();
        } catch (\Exception $e) {
            $tran->rollBack();
            Yii::$app->message->fail($e->getMessage());
        }
    }

    /**
     * 根据type和dependence_id获得管理员列表
     */
    public function actionManagers()
    {
        $type = Yii::$app->request->get('type', null);
        $dependence_id = Yii::$app->request->get('dependence_id', null);
        if (!isset($type) || !isset($dependence_id)) Yii::$app->message->fail('参数错误');
        //business: 1处室 2大厅 3学院
        //manager: 2学院 3处室
        $type = $type == BusinessModel::IDENTITY_COLLEGE ? ManagerModel::IDENTITY_COLLEGE : ManagerModel::IDENTITY_OFFICE;

        $where = ['type' => $type, 'dependence_id' => $dependence_id];

        $lists = ManagerModel::lists($where, null, null, ['name asc'], ['id', 'name']);

        Yii::$app->message->success($lists);
    }

    /*
     * 根据business_id查找指派的老师
     * */
    public function actionGetManager()
    {
        $business_id = Yii::$app->request->get('id');
        if (empty($business_id)) Yii::$app->message->fail('参数错误');
        $rs = BusinessDistributeModel::lists(['business_id' => $business_id], null, null, null, ['business_id', 'manager_id']);
        $manager_ids = array_unique(array_column($rs, 'manager_id'));
        $managers = Manager::id2managerDetails($manager_ids);
        $managers = array_column($managers, null, 'id');
        array_walk($rs, function (&$v) use ($managers) {
            $v['name'] = $managers[$v['manager_id']]['name'];
            $v['dependence_name'] = $managers[$v['manager_id']]['dependence_name'];
        });
        Yii::$app->message->success($rs);
    }

    //通过id和type获得业务详情
    public function actionOne()
    {
        $id = Yii::$app->get('id');
        $type = Yii::$app->get('type');
        switch ($type) {
            case 'college':
                $type = 3;
                break;
            case 'hall':
                $type = 2;
                break;
            case 'office':
                $type = 1;
                break;
        }
        if (!$id || !$type) Yii::$app->message->fail('参数错误');
        $rs = BusinessModel::one(['id' => $id, 'type' => $type]);
        Yii::$app->message->success($rs);
    }
}
