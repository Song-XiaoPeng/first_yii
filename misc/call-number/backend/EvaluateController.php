<?php

namespace apps\backend\controllers;

use core\models\BusinessModel;
use core\models\CollegeModel;
use core\models\DealLogModel;
use core\models\EvaluateLogModel;

use core\models\EvaluateScoreModel;
use core\models\EvaluateTagModel;
use core\models\ManagerModel;
use core\models\OfficeModel;
use core\models\ScoreElementsModel;
use core\models\TagsModel;
use core\models\UserModel;
use core\services\Business;
use core\services\Manager;
use Yii;
use core\controllers\BackendController;
use core\services\Place;
use core\services\User as UserService;

class EvaluateController extends BackendController
{
    public function highLightRules()
    {
        return [
            ['actions' => ['score', 'tags', 'evaluate-log', 'feedback'], 'rule' => 'evaluate'],
            ['actions' => ['score'], 'rule' => 'evaluate.score'],
            ['actions' => ['tags'], 'rule' => 'evaluate.tags'],
            ['actions' => ['evaluate-log'], 'rule' => 'evaluate.logs'],
            ['actions' => ['feedback'], 'rule' => 'evaluate.feedback']
        ];
    }

    public function accessRules()
    {
        return [
            ['allow', 'actions' => ['score', 'score-save', 'score-del'], 'rules' => ['score']],
            ['allow', 'actions' => ['tags', 'tag-save', 'tag-del'], 'rules' => ['tags']],
            ['allow', 'actions' => ['evaluate-log', 'evaluate-log-del', 'log-detail'], 'rules' => ['evalog']],
        ];
    }

    ###=====================================================================================
    ### 评分项:evaluate_log  type:1用户评分2用户打标签3管理员对用户打分 dependence_id:评分项id或标签id/score_elements
    ###=====================================================================================
    public function actionScore()
    {
        $limit = Yii::$app->page->getLimit();
        $rs = ScoreElementsModel::lists(['status' => 0], $limit[0], $limit[1], ['id ASC']);
        $rs = Manager::managerId2Name($rs);
        Yii::$app->page->setTotal($rs['count']);
        return $this->render('score', ['data' => $rs['lists']]);
    }

    public function actionScoreSave()
    {
        $data = [
            'name' => Yii::$app->request->post('name'),
            'manager_id' => Yii::$app->manager->uid,
            'createtime' => time(),
        ];
        $score_id = Yii::$app->request->post('scoreid');
        if ($score_id) {
            $data['id'] = $score_id;
        }

        if (ScoreElementsModel::one(['AND', ['name' => $data['name']], ['!=', 'id', $score_id], ['status' => 0]], 1)) Yii::$app->message->fail('评分项名称已存在，请重新输入');
        $rs = ScoreElementsModel::save($data);

        if ($rs >= 0) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail('添加失败');
        }
    }

    public function actionScoreDel()
    {
        $id = Yii::$app->request->post('id');
        $rs = ScoreElementsModel::update(['status' => 1], ['id' => $id]);
        if ($rs) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail('删除失败');
        }
    }

    ###=====================================================================================
    ### 标签管理
    ###=====================================================================================
    public function actionTags()
    {
        $name = Yii::$app->request->get('name', '');
        $type = Yii::$app->request->get('type', '');
        $nature = Yii::$app->request->get('nature', '');
        $start = Yii::$app->request->get('start', '');
        $end = Yii::$app->request->get('end', '');

        $where = [['status' => 0]];
        if ($name) {
            $tag_id = TagsModel::name2Ids($name);
            $where[] = ['id' => $tag_id];
        }
        if ($type) {
            $tmp = [0, 1];
            switch ($type) {
                case "user":
                    $tmp = 0;
                    break;
                case "sys":
                    $tmp = 1;
                    break;
            }
            $where[] = ['type' => $tmp];
        }
        if ($nature) {
            $tmp = [0, 1, 2];
            switch ($nature) {
                case "none":
                    $tmp = 0;
                    break;
                case "good":
                    $tmp = 1;
                    break;
                case "bad":
                    $tmp = 2;
                    break;
            }
            $where[] = ['nature' => $tmp];
        }
        if ($start && $end) {
            $where[] = [
                'and',
                ['>=', 'deal_time', strtotime($start)],
                ['<=', 'deal_time', strtotime($end)]
            ];
        }
        if (count($where) > 1) {
            array_unshift($where, 'AND');
        } else {
            $where = array_shift($where);
        }

        $limit = Yii::$app->page->getLimit();
        $rs = TagsModel::lists($where, $limit[0], $limit[1], ['id ASC']);
        $rs = UserService::uid2Name($rs);
        foreach ($rs['lists'] as &$v) {
            $v['type_name'] = $v['type'] == 0 ? '用户标签' : '系统标签';
            $v['nature_name'] = $v['nature'] == 0 ? '未知' : ($v['nature'] == 1 ? '好' : '坏');
        }

        Yii::$app->page->setTotal($rs['count']);
        return $this->render('tags', [
            'data' => $rs['lists'],
            'search' => [
                'name' => $name,
                'nature' => $nature,
                'type' => $type,
                'start' => $start,
                'end' => $end
            ]
        ]);
    }

    public function actionTagSave()
    {
        $id = Yii::$app->request->post('tagid');
        $data = [
            'name' => Yii::$app->request->post('name'),
            'type' => Yii::$app->request->post('type'),
            'nature' => Yii::$app->request->post('nature'),
            'createtime' => time(),
        ];

        if (TagsModel::one(['AND', ['name' => $data['name']], ['!=', 'id', $id], ['status' => 0]], 1)) Yii::$app->message->fail('标签名称已存在，请重新输入');
        $rs = TagsModel::update($data, ['id' => $id]);

        if ($rs >= 0) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail('保存失败');
        }
    }

    public function actionTagDel()
    {
        $id = Yii::$app->request->post('id');
        $rs = TagsModel::update(['status' => 1], ['id' => $id]);
        if ($rs) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail('删除失败');
        }
    }

    ###=====================================================================================
    ### 评价记录管理 需和业务处理记录关联 evaluate_log=>deal_log 查处评价人，被评价人
    ###=====================================================================================
    public function actionEvaluateLog()
    {
        $business_name = Yii::$app->request->get('business_name', '');
        $manager = Yii::$app->request->get('manager', '');
        $number = Yii::$app->request->get('number', '');
        $user = Yii::$app->request->get('user', '');
        $start = Yii::$app->request->get('start', '');
        $end = Yii::$app->request->get('end', '');

        $where = [];
        if ($business_name) {
            $business_id = BusinessModel::name2Ids($business_name);
            $where[] = ['business_id' => $business_id];
        }
        if ($manager) {
            $manager_id = ManagerModel::name2Ids($manager);
            $where[] = ['manager_id' => $manager_id];
        }
        if ($number) {
            $manager_id = ManagerModel::one(['number' => $number]) ? ManagerModel::one(['number' => $number])['id'] : 0;
            $where[] = ['manager_id' => $manager_id];
        }
        if ($user) {
            $uid = UserModel::name2Ids($user);
            $where[] = ['uid' => $uid];
        }
        if ($start && $end) {
            $where[] = [
                'and',
                ['>=', 'deal_time', strtotime($start)],
                ['<=', 'deal_time', strtotime($end)]
            ];
        }
        if (count($where) > 1) {
            array_unshift($where, 'AND');
        } else {
            $where = array_shift($where);
        }
        $deal_ids = array_column(DealLogModel::lists($where), 'id');
//        echo '<pre>';
//        var_dump($where);
//        die;
        if ($deal_ids) {
            $where = ['deal_id' => $deal_ids];
        } else {
            return $this->render('eva-log', [
                'search' => [
                    'manager' => $manager,
                    'business_name' => $business_name,
                    'user' => $user,
                    'number' => $number,
                    'start' => $start,
                    'end' => $end
                ]
            ]);
        }
        $limit = Yii::$app->page->getLimit();
        $rs = EvaluateLogModel::lists($where, $limit[0], $limit[1], ['createtime DESC']);
        //业务评价表
        $deal_ids = array_unique(array_column($rs['lists'], 'deal_id'));
        $deal_logs = DealLogModel::lists(['id' => $deal_ids]);
        $deal_logs = array_column($deal_logs, null, 'id');


        //$office = OfficeModel::assoc([]);
        //$office['dependence_id'];

        //业务关联表
        $business_ids = array_unique(array_column($deal_logs, 'business_id'));
        $businesses = BusinessModel::lists(['id' => $business_ids]);
        $businesses = array_column($businesses, null, 'id');
        //评分类型关联  类型：用户评分1,老师评分2


        //评价记录-标签关联


        /* $type_score = EvaluateLogModel::assoc(['type' => EvaluateLogModel::TYPE_SCORE], 'dependency_id', 'dependency_id');
         $type_tag = EvaluateLogModel::assoc(['type' => EvaluateLogModel::TYPE_TAG], 'dependency_id', 'dependency_id');
         $dependency_score = ScoreElementsModel::lists(['id' => $type_score]);
         $dependency_tag = TagsModel::lists(['id' => $type_tag]);
         $type = [
             EvaluateLogModel::TYPE_SCORE => array_column($dependency_score, null, 'id'),
             EvaluateLogModel::TYPE_TAG => array_column($dependency_tag, null, 'id')
         ];*/
        //管理员关联
        $manager_ids = array_unique(array_column($deal_logs, 'manager_id'));
        $managers = ManagerModel::lists(['id' => $manager_ids]);
        $managers = array_column($managers, null, 'id');
        //部门关联
        $college_ids = ManagerModel::assoc(['type' => ManagerModel::IDENTITY_COLLEGE], 'dependence_id', 'dependence_id');
        $office_ids = ManagerModel::assoc(['type' => [ManagerModel::IDENTITY_OFFICE, ManagerModel::IDENTITY_MANAGER]], 'dependence_id', 'dependence_id');
        $departments = [
            ManagerModel::IDENTITY_COLLEGE => CollegeModel::assoc(['id' => $college_ids], 'id', 'name'),
            ManagerModel::IDENTITY_OFFICE => OfficeModel::assoc(['id' => $office_ids], 'id', 'name')
        ];
        //用户关联
        $uids = array_unique(array_column($deal_logs, 'uid'));
        $users = UserModel::lists(['AND', ['id' => $uids], ['!=', 'id', 0]]);
        $users = array_column($users, null, 'id');

        foreach ($managers as &$v) {
            if ($v['type'] == ManagerModel::IDENTITY_SYSTEM) {
                $v['department'] = '无';
            } else {
                $v['type'] = $v['type'] == ManagerModel::IDENTITY_MANAGER ? ManagerModel::IDENTITY_OFFICE : $v['type'];
                $v['department'] = !empty($departments[$v['type']][$v['dependence_id']]) ? $departments[$v['type']][$v['dependence_id']] : '未知';
            }
        }

        foreach ($deal_logs as &$v) {
            $v['manager'] = !empty($managers[$v['manager_id']]) ? $managers[$v['manager_id']] : [];
            $v['user'] = !empty($users[$v['uid']]) ? $users[$v['uid']] : [];
            $v['business'] = !empty($businesses[$v['business_id']]) ? $businesses[$v['business_id']] : [];
        }

        foreach ($rs['lists'] as &$v) {
            $v['deal_log'] = $deal_logs[$v['deal_id']];
        }

//        echo '<pre>';
//        var_dump($rs['lists']);
//        die;
        Yii::$app->page->setTotal($rs['count']);
        return $this->render('eva-log', [
            'data' => $rs['lists'],
            'search' => [
                'manager' => $manager,
                'business_name' => $business_name,
                'user' => $user,
                'number' => $number,
                'start' => $start,
                'end' => $end
            ]
        ]);
    }

    public function actionEvaluateLogDel()
    {
        $id = Yii::$app->request->post('id');
        $rs = EvaluateLogModel::update(['status' => 1], ['id' => $id]);
        if ($rs) {
            Yii::$app->message->success();
        } else {
            Yii::$app->message->fail('删除失败');
        }
    }

    public function actionLogDetail()
    {
        $id = Yii::$app->request->get('id');
        if (!$id) Yii::$app->message->fail('参数错误');
        $one = EvaluateLogModel::one(['id' => $id]);
        //标签
        $eva_tags = EvaluateTagModel::lists(['evaluate_id' => $id], null, null, null, ['tags_id']);
        $tags_ids = array_column($eva_tags, 'tags_id');
        $tag_name = TagsModel::assoc(['id' => $tags_ids], 'id', 'name');
        foreach ($eva_tags as &$v) {
            $v['name'] = $tag_name[$v['tags_id']];
        }
        //评分项
        $eva_scores = EvaluateScoreModel::lists(['evaluate_id' => $id, 'type' => EvaluateScoreModel::TYPE_USER], null, null, null, ['score_id', 'score']);
        $score_ids = array_column($eva_scores, 'score_id');
        $score_name = ScoreElementsModel::assoc(['id' => $score_ids], 'id', 'name');
        foreach ($eva_scores as &$v) {
            $v['name'] = $score_name[$v['score_id']];
        }

        $data = [
            'score' => !empty($eva_scores) ? $eva_scores : '',
            'tags' => !empty($eva_tags) ? $eva_tags : '',
            'desc' => !empty($one['desc']) ? $one['desc'] : ''
        ];

        Yii::$app->message->success($data);
    }
}
