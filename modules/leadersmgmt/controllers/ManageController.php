<?php

namespace app\modules\leadersmgmt\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Data;
use app\models\VotersdbVoters;
use app\models\VotersdbLeaders;
use app\models\VotersdbMembers;

class ManageController extends \yii\web\Controller
{
    public $layout = '/commonLayout';
    public $route_nav;
    public $viewPath = 'app/modules/leadersmgmt/views';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // Pages that are included in the rule set
                'only'  => ['index'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['index'],
                        'roles'     => ['@']
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    // Action when user is denied from accessing a page
                    if (Yii::$app->user->isGuest) {
                        $this->goHome();
                    } else {
                        $this->redirect(['/dashboard']);
                    }
                }
            ]
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function init()
    {
        if(Yii::$app->user->isGuest) {
            $url = '/login';
        } else {
            $url = '/dashboard';
        }
    }

    public function actionIndex()
    {
        $leadersModel = new VotersdbLeaders;
        $records = $leadersModel->getLeadersList();
        return $this->render('list', [
            'records'       => $records
        ]);
    }

    public function actionDelete($id)
    {
        $leadersModel = new VotersdbLeaders;
        $params_leader = ['voter_id' => $id, 'status' => 'active'];
        $leaders = Data::findRecords($leadersModel, null, $params_leader);
        if(!empty($leaders)){
            $membersModel = new VotersdbMembers;
            $params_member = ['leader_id' => $leaders['id'], 'status' => 'active'];
            $members = Data::findRecords($membersModel, null, $params_member,'all');
            foreach($members as $member){
                if(!empty($member)){
                    $member->status = 'deleted';
                    if ($member->save(false)) {
                       //
                    }
                }
            }
            $leaders->status = 'deleted';
            if ($leaders->save(false)) {
              Yii::$app->session->setFlash('success',"Leader Successfully Deleted");
              $this->redirect('/leadersmgmt/manage/list');
            }
        }
    }

    public function actionAdd()
    {
        $leadersModel = new VotersdbLeaders;

        return $this->render('create', [
            'model'     => $leadersModel
        ]);
    }

    public function actionEdit($id)
    {
        $errorMsg = 0;
        $leadersModel = new VotersdbLeaders;
        $membersModel = new VotersdbMembers;
        $votersModel  = new VotersdbVoters;
        $params       = ['status' => 'active', 'id' => $id];
        $record       = Data::findRecords($leadersModel, null, $params);
        if(count($record) == 0) {
            return $this->redirect('/leadersmgmt/manage/list');
        }
        $params       = ['status' => 'active', 'leader_id' => $id];
        $members      = Data::findRecords($membersModel, null, $params, 'all');
        $list         = [];
        $memberIds    = [];
        if(count($members) != 0) {
            foreach($members as $value) {
                $params = ['status' => 'active', 'id' => $value['voter_id']];
                $voter             = Data::findRecords($votersModel, null, $params );
                if(count($voter) != 0) {
                    $temp = [];
                    $temp['voter_id'] = $value['voter_id'];
                    if(empty($voter['middle_name'])) {
                        $temp['name'] = $voter['first_name'].' '.$voter['last_name'];
                    } else {
                        $temp['name'] = $voter['first_name'].' '.$voter['middle_name'].' '.$voter['last_name'];
                    }
                    array_push($list, $temp);
                    array_push($memberIds, $value['voter_id']);
                }
            }
        }

        if(Yii::$app->request->isPost) {
            $precinct = strtoupper(Yii::$app->request->post('assigned_precinct'));
            $members  = Yii::$app->request->post('members');
            if(count($members) == 0) {
                $members = [];
            }

            if($leadersModel->saveMembers($id, $precinct, $members, $memberIds)) {
                return $this->redirect('/leadersmgmt/manage/list');
            } else {
                $errorMsg = 1;
            }
        }


        return $this->render('create', [
            'model'     => $record,
            'members'   => $list,
            'error'     => $errorMsg
        ]);
    }

    public function actionGetlist()
    {
        $keyword = $_GET['query'];
        $leadersModel = new VotersdbLeaders;
        $records = $leadersModel->getList($keyword);
        $arr = [];
        $arr['suggestions'] = [];
        foreach($records as $rec) {
            if(empty($rec['middle_name'])) {
                $temp = [];
                $temp['value'] = $rec['first_name']." ".$rec['last_name'];
            } else {
                $temp['value'] = $rec['first_name']." ".$rec['middle_name']." ".$rec['last_name'];
            }
            $temp['data'] = $rec['id'];
            array_push($arr['suggestions'], $temp);
        }
        return json_encode($arr);
    }

    public function actionMemberlist($id)
    {
        $model          = new VotersdbMembers;
        $votersModel    = new VotersdbVoters;
        $params         = ['status' => 'active', 'leader_id' => $id];
        $records        = Data::findRecords($model, null, $params, 'all');
        $list           = [];

        if(count($records)!=0) {
            foreach($records as $value) {
                $temp   = [];
                $params = ['status' => 'active', 'id' => $value['voter_id']];
                $voter  = Data::findRecords($votersModel, null, $params );
                if(count($voter) != 0) {
                    $temp = [];
                    $temp['id'] = $value['id'];
                    $temp['vin'] = $voter['voters_no'];
                    $temp['voter_id'] = $value['voter_id'];
                    if(empty($voter['middle_name'])) {
                        $temp['name'] = $voter['first_name'].' '.$voter['last_name'];
                    } else {
                        $temp['name'] = $voter['first_name'].' '.$voter['middle_name'].' '.$voter['last_name'];
                    }
                    array_push($list, $temp);
                }
            }
        }

        return $this->render('viewList', [
            'list'     => $list
        ]);
    }

    public function actionLeader($operator, $id) {
        // Default Variables needed
        $errorCode = 0;
        $msg = '';
        $url = '/votersmgmt/manage/list';
        // Initialize Models
        $model = new VotersdbMembers;
        $leadersModel = new VotersdbLeaders;

        $params = ['voter_id' => $id, 'status' => 'active'];
        $record = Data::findRecords($model, null, $params, 'all');
        if(count($record) != 0) {
            $errorCode = 1;
            $msg = 'Voter is already a member';
        } else {
            if(!$leadersModel->saveLeader($leadersModel, $id, $operator)) {
              $errorCode = 1;
              $msg = 'An error occured. Please try again later.';
            }
        }
        return json_encode(['error' => $errorCode, 'msg' => $msg, 'url' => $url]);
    }

    public function actionDeletemember($member_id)
    {
        $model = new VotersdbMembers;
    }


}


?>
