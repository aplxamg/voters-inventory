<?php

namespace app\modules\leadersmgmt\controllers;

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
    public $viewPath = 'app/modules/leadersmgmt/views';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // Pages that are included in the rule set
                'only'  => ['index', 'delete', 'add', 'edit', 'getlist', 'memberlist', 'leader', 'deletemember'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['index', 'delete', 'add', 'edit', 'getlist', 'memberlist', 'leader', 'deletemember'],
                        'roles'     => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $identity = User::initUser();
                            $adminAccess = ['index', 'delete', 'add', 'edit', 'getlist', 'memberlist', 'leader', 'deletemember'];
                            $encoderAccess = [''];
                            $leaderAccess = ['delete', 'add', 'edit', 'getlist', 'memberlist', 'leader', 'deletemember'];

                            if($identity->user_type == 'admin' && in_array($action->id, $adminAccess)) {
                                return true;
                            } else if ($identity->user_type == 'encoder' && in_array($action->id, $encoderAccess)) {
                                return true;
                            } else if ($identity->user_type == 'leader' && in_array($action->id, $leaderAccess)) {
                                return true;
                            } else { // falls for encoder user type
                                return false;
                            }
                        }
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
        $errorCode = 0;
        $msg = '';
        $url = '/leadersmgmt/manage/list';
        if(!$leadersModel->deleteLeader($leadersModel, $id)) {
            $errorCode = 1;
            $msg = 'An error occured while deleting the data';
        }

        return json_encode(['error' => $errorCode, 'msg' => $msg, 'url' => $url]);

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
        $identity       = User::initUser();
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
            $result = $leadersModel->saveMembers($id, $precinct, $members, $memberIds);

            if($result != false) {
                if(gettype($result) == 'boolean') {
                    if($identity->user_type == 'admin') {
                        return $this->redirect('/leadersmgmt/manage/memberlist/'.$id);
                    } else {
                        return $this->redirect('/leadersmgmt/manage/members');
                    }
                } else {
                    $errorMsg = [];
                    foreach ($result as $value) {
                        $params = ['status' => 'active', 'id' => $value];
                        $rec = Data::findRecords($votersModel, null, $params);
                        if(empty($record['middle_name'])) {
                            $name = $rec['first_name']." ".$rec['last_name'];
                        } else {
                            $name = $rec['first_name']." ".$rec['middle_name']." ".$rec['last_name'];
                        }

                        array_push($errorMsg, $name);
                    }
                }

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

    /* Used for autocomplete */
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

    public function actionGetlists()
    {
        $leadersModel = new VotersdbLeaders;
        $records = $leadersModel->getLists();

        if(!empty($records)) {
            $arr = [];
            $arr['data'] = [];
            foreach($records as $rec) {
                $temp = [];
                $temp['id'] = $rec['id'];
                $temp['vin'] = $rec['voters_no'];
                $temp['precinct'] = $rec['precinct_no'];
                if(empty($rec['middle_name'])) {
                    $temp['name'] = $rec['first_name']." ".$rec['last_name'];
                } else {
                    $temp['name'] = $rec['first_name']." ".$rec['middle_name']." ".$rec['last_name'];
                }
                array_push($arr['data'], $temp);
            }
            return json_encode($arr);
        } else {
            return json_encode('failed');
        }
    }

    public function actionMemberlist($id)
    {
        $identity       = User::initUser();
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
                    $temp['vote']   = $voter['voting_status'];
                    $temp['voter_id'] = $voter['id'];
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
            'list'          => $list,
            'identity'      => $identity,
            'id'            => $id
        ]);
    }

    public function actionMembers()
    {
        $identity       = User::initUser();
        $model          = new VotersdbMembers;
        $votersModel    = new VotersdbVoters;
        $leadersModel   = new VotersdbLeaders;
        $params         = ['status' => 'active', 'user_id' => $identity->id];
        $leader         = Data::findRecords($leadersModel, null, $params);
        $params         = ['status' => 'active', 'leader_id' => $leader->id];
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
                    $temp['vote']   = $voter['voting_status'];
                    $temp['voter_id'] = $voter['id'];
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
            'list'          => $list,
            'identity'      => $identity,
            'id'            => $leader->id
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

    public function actionDeletemember($member, $leader)
    {
        $errorCode = 0;
        $msg = '';
        $model  = new VotersdbMembers;
        $params = ['id' => $member, 'leader_id' => $leader,'status' => 'active'];
        $record = Data::findRecords($model, null, $params);
        if(empty($record)) {
            $errorCode = 1;
            $msg = 'Voter is not your member';
        } else {
            $record->status = 'deleted';
            if(!$record->save()) {
                $errorCode = 1;
                $msg = 'An error occured. Please try again later';
            }
        }

        $url = '/leadersmgmt/manage/memberlist/' . $leader;
        return json_encode(['error' => $errorCode, 'msg' => $msg, 'url' => $url]);
    }

}


?>
