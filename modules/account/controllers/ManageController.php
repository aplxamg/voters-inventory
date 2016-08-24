<?php

namespace app\modules\account\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\User;
use app\components\helpers\Data;
use app\models\Users;
use app\models\VotersdbVoters;
use app\models\VotersdbMembers;
use app\models\VotersdbLeaders;


class ManageController extends \yii\web\Controller
{
    public $layout = '/commonLayout';
    public $route_nav;
    public $viewPath = 'app/modules/account/views';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // Pages that are included in the rule set
                'only'  => ['index', 'add', 'edit', 'delete', 'check'],
                'rules' => [
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['index', 'add', 'edit', 'delete', 'check'],
                        'roles'     => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $identity = User::initUser();
                            $adminAccess = ['index', 'add', 'edit', 'delete', 'check']; // functions / pages accessible by the admin
                            $encoderAccess = ['']; // functions / pages accessible by the encoder
                            $leaderAccess = [''];

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
        $userModel = new Users;
        $leadersModel = new VotersdbLeaders;
        $votersModel = new VotersdbVoters;
        $params = ['status' => 'active','user_type' => ['encoder','leader']];
        $records = Data::findRecords($userModel, null, $params, 'all');
        $arr = [];

        foreach($records as $rec) {
            $temp = [];
            $temp['id']         = $rec['id'];
            $temp['user_type']  = $rec['user_type'];
            $temp['username']   = $rec['username'];
            $temp['ins_time']   = $rec['ins_time'];
            $temp['password']   = '';
            if($rec['user_type'] == 'leader') {
                $params = ['status' => 'active', 'user_id' => $rec['id']];
                $voter_id = Data::findRecords($leadersModel, 'voter_id', $params);
                $params = ['status' => 'active', 'id' => $voter_id];
                $voter = Data::findRecords($votersModel, null, $params);
                if(!empty($voter->middle_name)) {
                    $password = $voter->id.$this->getFirsts($voter->last_name).$this->getFirsts($voter->first_name).$this->getFirsts($voter->middle_name).implode('', explode('/', $voter->birthdate));
                } else {
                    $password = $voter->id.$this->getFirsts($voter->last_name).$this->getFirsts($voter->first_name).implode('', explode('/', $voter->birthdate));
                }


                $temp['password'] = $password;
            }

            array_push($arr, $temp);
        }


         return $this->render('list', [
            'records'       => $arr
        ]);
    }

    public function actionAdd()
    {
        $error = '';
        $userModel = new Users;
        if(Yii::$app->request->isPost) {
            $values = Yii::$app->request->post('Users');
            if($userModel->saveAccount($userModel,$values)) {
                return $this->redirect('/account/manage/list');
            } else {
                $errorMsg = 'An error occured while saving the data';
            }
        }
        return $this->render('create', [
            'model' => $userModel,
            'error' => $error
        ]);
    }

    public function actionEdit($id)
    {
        $error = '';
        $userModel = new Users;

        $params = ['id' => $id, 'status' => 'active'];
        $record = Data::findRecords($userModel, null, $params);
        if(empty($record)) {
            return $this->redirect('/account/manage/list');
        }

        if(Yii::$app->request->isPost) {
            $values = Yii::$app->request->post('Users');
            if($userModel->saveAccount($userModel,$values, $id)) {
                return $this->redirect('/account/manage/list');
            } else {
                $error = 'An error occured while updating the data';
            }
        }

        return $this->render('create',[
            'model'     => $record,
            'error'     => $error,
            'id'        => $id
        ]);
    }

    public function actionDelete($id)
    {
        $errorCode = 0;
        $msg = '';

        $model = new Users;
        $params = ['id' => $id, 'status' => 'active'];
        $record = Data::findRecords($model, null, $params);

        if(empty($record)) {
            $errorCode = 1;
            $msg = 'Account not found';
        } else {
            $record->status = 'inactive';
            if(!$record->save()) {
                $errorCode = 1;
                $msg = 'An error occured while deleting account';
            }
        }

        $url = '/account/manage/list';
        return json_encode([
            'error' => $errorCode,
            'msg'   => $msg,
            'url'   => $url
        ]);
    }

    public function actionCheck($username)
    {
        $model = new Users;
        $params = ['username' => $username, 'status' => 'active'];
        $record = Data::findRecords($model, null, $params, 'all');
        return count($record);
    }

      private function getFirsts($words) {
        $acronym = '';
        if($words != null) {
            $words = explode(' ', strtolower($words));
            foreach ($words as $w) {
              $acronym .= $w[0];
            }
        }
        return $acronym;
    }


}


?>
