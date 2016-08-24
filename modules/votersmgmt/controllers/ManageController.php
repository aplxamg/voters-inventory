<?php

namespace app\modules\votersmgmt\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Data;
use app\components\helpers\User;
use app\models\Users;
use app\models\VotersdbVoters;
use app\models\VotersdbLeaders;
use app\models\VotersdbMembers;

class ManageController extends \yii\web\Controller
{
    public $layout = '/commonLayout';
    public $route_nav;
    public $viewPath = 'app/modules/votersmgmt/views';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // Pages that are included in the rule set
                'only'  => ['index', 'view', 'edit', 'add', 'delete', 'vote'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['index', 'view', 'edit', 'add', 'delete', 'vote'],
                        'roles'     => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $identity = User::initUser();
                            $adminAccess = ['index', 'view', 'edit', 'add', 'delete', 'vote']; // functions / pages accessible by the admin
                            $encoderAccess = ['index', 'view', 'edit', 'add', 'delete']; // functions / pages accessible by the encoder
                            $leaderAccess = ['vote'];

                            if($identity->user_type == 'admin' && in_array($action->id, $adminAccess)) {
                                return true;
                            } else if ($identity->user_type == 'encoder' && in_array($action->id, $encoderAccess)) {
                                return true;
                            } else if ($identity->user_type == 'leader' && in_array($action->id, $leaderAccess)) {
                                return true;
                            } else { // falls for encoder user type
                                return false;
                            }

                        },
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
        $identity = User::initUser();
        $votersModel = new VotersdbVoters;
        $leaderModel = new VotersdbLeaders;
        $params = ['status' => 'active'];
        $records = Data::findRecords($votersModel, null, $params, 'all');
        $arr = [];
        foreach ($records as $rec) {
            $temp  = [];
            $temp['id']     = $rec['id'];
            $temp['vin']    = $rec['voters_no'];
            if(empty($voter['middle_name'])) {
                $temp['name'] = ucfirst($rec['first_name']).' '.ucfirst($rec['last_name']);
            } else {
                $temp['name'] = ucfirst($rec['first_name']).' '.ucfirst($rec['middle_name']).' '.ucfirst($rec['last_name']);
            }
            $temp['address']    = $rec['address'];
            $temp['birthdate']  = $rec['birthdate'];
            $temp['precinct']   = $rec['precinct_no'];
            $temp['voting_status']   = $rec['voting_status'];
            $params = ['status' => 'active', 'voter_id' => $rec['id']];
            $leader = Data::findRecords($leaderModel, null, $params);
            $temp['leader'] = count($leader);
            $temp['assigned_precinct'] = (count($leader) != 0) ? $leader['assigned_precinct'] : '';
            array_push($arr, $temp);
        }

        return $this->render('list', [
            'records'       => $arr,
            'identity'     => $identity
        ]);
    }

    public function actionView($id) {

        if(Yii::$app->request->isPost) {
            if(Yii::$app->request->post('view') === 'Back') {
                return $this->redirect('/votersmgmt/manage/list');
            }
        }
        $votersModel = new VotersdbVoters;
        $params = ['id' => $id];
        $voter = Data::findRecords($votersModel, null, $params, 'one');
         return $this->render('view', ['voter' => $voter]);
    }

    public function actionEdit($id) {

        $votersModel = new VotersdbVoters;

        if(Yii::$app->request->isPost) {

            $values = Yii::$app->request->post('VotersdbVoters');
            $votersModel->saveVoter($votersModel,$id,$values);
            return $this->redirect('/votersmgmt/manage/list');
        }

        $params = ['id' => $id];
        $voter = Data::findRecords($votersModel, null, $params, 'one');
        return $this->render('edit', ['voter' => $voter]);
    }
    /**     @author     Anecita M Gabisan
    **      @created    2016-07-02
    **
    **      errorMsg
    **          0   = No error
    **          1   = Error on saving record
    **          2   = Record already exists
    **/
    public function actionAdd()
    {
        $votersModel = new VotersdbVoters;
        $errorMsg   = 0;
        if(Yii::$app->request->isPost) {
            if(Yii::$app->request->post('save') === 'Save') {
                $event = 'list';
            } else {
                $event = 'add';
            }
            $values = Yii::$app->request->post('VotersdbVoters');
            $params = [ 'status'        => 'active',
                        'first_name'    => strtoupper(trim($values['first_name'])),
                        'last_name'     => strtoupper(trim($values['last_name']))
                      ];
            $record = Data::findRecords($votersModel, null, $params);
            if(count($record) == 0) {
                if($votersModel->saveVoter($votersModel, null, $values)) {
                    return $this->redirect('/votersmgmt/manage/'.$event);
                } else {
                    $errorMsg = 1;
                }
            } else {
                $errorMsg = 2;
            }
        }
        return $this->render('create', [
            'model' => $votersModel,
            'error' => $errorMsg
        ]);
    }

    public function actionDelete($id)
    {
        $errorCode = 0;
        $msg = '';
        $votersModel = new VotersdbVoters;
        $params = ['id' => intval($id), 'status' => 'active'];
        $voters = Data::findRecords($votersModel, null, $params);
        if(!empty($voters)){
            $leadersModel = new VotersdbLeaders;
            $params_leader = ['voter_id' => $voters['id'], 'status' => 'active'];
            $leaders = Data::findRecords($leadersModel, null, $params_leader);
            if(!empty($leaders)){
                $errorCode = 1;
                $msg = 'Voter is a Leader';
            }else{
                //not a leader
                $membersModel = new VotersdbMembers;
                $params_member = ['voter_id' => $voters['id'], 'status' => 'active'];
                $members = Data::findRecords($membersModel, null, $params_member);
                if(!empty($members)){
                    $member->status = 'deleted';
                    if ($member->save(false)) {
                       //
                    }
                }
                $voters->status = 'deleted';
                if($voters->save(false)){
                }
            }
        }
        $url = '/votersmgmt/manage/list';
        return json_encode(['error' => $errorCode, 'msg' => $msg, 'url' => $url]);
    }

    public function actionVote($operator, $id, $user, $leader)
    {
        $errorCode = 0;
        $msg = '';
        $url = '/votersmgmt/manage/list';
        if($user == 'leader') {
            $url = '/leadersmgmt/manage/memberlist/'.$leader;
        }

        $model = new VotersdbVoters;
        if(!$model->updateVote($id, $operator)) {
            $errorCode = 1;
            $msg = 'An error occured. Please try again later';
        }
        return json_encode(['error' => $errorCode, 'msg' => $msg, 'url' => $url]);

    }

    public function actionChart($id)
    {
        // Initialize Models
        $votersModel        = new VotersdbVoters;
        $leadersModel       = new VotersdbLeaders;
        $membersModel       = new VotersdbMembers;
        $labels             = ['Y', 'N'];

        $summary['labels'] = ['Voted', 'Not Voted'];
        $summary['datasets'] = [];
        $datasets['label']  = 'Count';
        $datasets['borderWidth'] = 1;
        $datasets['backgroundColor'] = [
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 99, 132, 0.2)'
        ];
        $datasets['borderColor'] = [
            'rgba(54, 162, 235, 1)',
            'rgba(255,99,132,1)'
        ];
        $datasets['data']    = [];
        foreach ($labels as $label) {
            if($id == 'null') {
                array_push($datasets['data'], $votersModel->countByVote($label));
            } else {
                array_push($datasets['data'], $leadersModel->getVoterCountByMembers($id,$label));
            }
        }
        array_push($summary['datasets'], $datasets);
        return json_encode($summary);
    }

    public function actionReset()
    {
        $model = new VotersdbVoters;
        $model->updateAll(['voting_status' => 'N'], 'voting_status = "Y"');
        $errorCode = 0;
        $msg = '';
        $url = '/dashboard';
        return json_encode(['error' => $errorCode, 'msg' => $msg, 'url' => $url]);
    }

    public function actionDeleteall()
    {
        //Initialize models
        $model = new VotersdbVoters;
        // Defaults
        $errorCode = 0;
        $msg = '';
        $url = '/dashboard';

        if(!$model->deleteData()) {
            $errorCode = 1;
            $msg = 'An errror occured while deleting data';
        }
        return json_encode(['error' => $errorCode, 'msg' => $msg, 'url' => $url]);
    }



}


?>
