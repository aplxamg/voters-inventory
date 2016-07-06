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


}


?>
