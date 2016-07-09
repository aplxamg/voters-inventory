<?php

namespace app\modules\votersmgmt\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Data;
use app\components\helpers\User;
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
                'only'  => ['index', 'view', 'edit', 'add', 'delete'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['index', 'view', 'edit', 'add', 'delete'],
                        'roles'     => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $identity = User::initUser();
                            $adminAccess = ['index', 'view', 'edit', 'add', 'delete']; // functions / pages accessible by the admin
                            $encoderAccess = ['index', 'view', 'edit', 'add', 'delete']; // functions / pages accessible by the encoder

                            if($identity->user_type == 'admin' && in_array($action->id, $adminAccess)) {
                                return true;
                            } else if ($identity->user == 'encoder' && in_array($action->id, $encoderAccess)) {
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
        $votersModel = new VotersdbVoters;
        $params = ['status' => 'active'];
        $records = Data::findRecords($votersModel, null, $params, 'all');
        $arr = [];
        /*
            foreach record here
                $temp = [];
                $temp['id'] = $rec['id'];
                $params = ['status' => 'active', 'voter_id' => $rec['id']];
                $records = Data::findRecords(leaderModel, null, $params, 'one');
                $temp['leader'] = (count($records) != 0) ? $records['id'] : 0;
                array_push($arr, $temp);
        */
        return $this->render('list', [
            'records'       => $records
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
        $votersModel = new VotersdbVoters;
        $params = ['id' => intval($id), 'status' => 'active'];
        $voters = Data::findRecords($votersModel, null, $params);
        if(!empty($voters)){
            $leadersModel = new VotersdbLeaders;
            $params_leader = ['voter_id' => $voters['id'], 'status' => 'active'];
            $leaders = Data::findRecords($leadersModel, null, $params_leader);
            if(!empty($leaders)){
                //error message
                Yii::$app->session->setFlash('error',"Voter is a Leader");
                $this->redirect('/votersmgmt/manage/list');
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
                    Yii::$app->session->setFlash('success',"Voter Successfully Deleted");
                    $this->redirect('/votersmgmt/manage/list');
                }
            }
        }
    }

}


?>
