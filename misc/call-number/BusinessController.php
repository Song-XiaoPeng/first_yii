<?php

namespace apps\backend\controllers;

use core\models\BusinessDistributeModel;
use core\models\BusinessModel;
use core\models\CollegeModel;
use core\models\HallModel;
use core\models\ManagerModel;
use core\models\OfficeModel;
use Yii;
use core\controllers\BackendController;

class BusinessController extends BackendController
{
    public function highLightRules()
    {
        return [
            ['actions' => ['business','hall','office','college'], 'rule' => 'business'],
            ['actions' => ['business'], 'rule' => 'business.business'],
            ['actions' => ['office'], 'rule' => 'business.office'],
            ['actions' => ['hall'], 'rule' => 'business.hall'],
            ['actions' => ['college'], 'rule' => 'business.college'],
        ];
    }

    ###=====================================================================================
    ### 业务管理
    ###=====================================================================================
    public function actionBusiness()
    {
        $limit = Yii::$app->page->getLimit();
        $rs = BusinessModel::lists([], $limit[0], $limit[1], ['id ASC']);

        Yii::$app->page->setTotal($rs['count']);

        $office = OfficeModel::lists([], null, null, ['id ASC'], ['id', 'name']);
        $college = CollegeModel::lists([], null, null, ['id ASC'], ['id', 'name']);
        $hall = HallModel::lists([], null, null, ['id ASC'], ['id', 'name']);
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
            $v['office_name'] = !empty($v['office_id']) ? $office[$v['office_id']]['name'] : '';
            $v['college_name'] = !empty($v['college_id']) ? $college[$v['college_id']]['name'] : '';
            $v['hall_name'] = !empty($v['hall_id']) ? $hall[$v['hall_id']]['name'] : '';
        }

        return $this->render('business', [
            'data' => $rs['lists'],
            'office' => $office,
            'college' => $college,
            'hall' => $hall,
            'type' => $type,
        ]);
    }

    public function actionBusinessSave()
    {
        $data = [
            'name' => Yii::$app->request->post('name'),
            'require' => Yii::$app->request->post('require'),
            'type' => Yii::$app->request->post('type'),
            'office_id' => Yii::$app->request->post('office_id'),
            'college_id' => Yii::$app->request->post('college_id'),
            'hall_id' => Yii::$app->request->post('hall_id'),
            'createtime' => time(),
        ];
        $business_id = Yii::$app->request->post('businessid');
        if ($business_id) {
            $data['id'] = $business_id;
        }
        $where = [
            'AND',
            ['name' => $data['name']],
            ['!=', 'id', $business_id],
            ['=', 'office_id', $data['office_id']],
            ['=', 'college_id', $data['college_id']],
            ['=', 'hall_id', $data['hall_id']],
            ['=', 'type', $data['type']],
        ];
        if (BusinessModel::one($where, 1)) Yii::$app->message->fail('业务名称已存在，请重新输入');

        $business_id = BusinessModel::save($data);

        if ($business_id >= 0) {
            unset($data);
            $data = [
                'business_id' => $business_id,
                'window' => Yii::$app->request->post('window'),
                'manager_id' => Yii::$app->request->post('manager_id')
            ];

            $rs = BusinessDistributeModel::save($data);
            $rs ? Yii::$app->message->success() : Yii::$app->message->fail('大厅业务分发失败');
        } else {
            Yii::$app->message->fail('添加失败');
        }
    }

    public function actionBusinessDel()
    {
        $id = Yii::$app->request->post('id');
        $rs = BusinessModel::delete(['id' => $id]);
        if ($rs) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail('删除失败');
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
        $where = ['type' => $type, 'dependence_id' => $dependence_id];

        $lists = ManagerModel::lists($where, null, null, ['name asc'], ['id', 'name']);

        Yii::$app->message->success($lists);
    }
    ###=====================================================================================
    ### 学院业务管理
    ###=====================================================================================
    public function actionCollege()
    {
        return $this->render('college');
    }

    ###=====================================================================================
    ### 大厅业务管理
    ###=====================================================================================
    public function actionHall()
    {
        return $this->render('hall');
    }
    ###=====================================================================================
    ### 处室业务管理
    ###=====================================================================================
    public function actionOffice()
    {
        return $this->render('office');
    }

    //通过id和type获得业务详情
    public function actionOne()
    {
        $id = Yii::$app->get('id');
        $type = Yii::$app->get('type');
        switch($type){
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
        if(!$id || !$type) Yii::$app->message->fail('参数错误');
        $rs = BusinessModel::one(['id'=>$id,'type'=>$type]);
        Yii::$app->message->success($rs);
    }
}
