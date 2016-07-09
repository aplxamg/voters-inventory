<?php

namespace app\modules\leadersmgmt\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Data;
use app\models\VotersdbVoters;
use app\models\VotersdbLeaders;

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

    public function actionAdd()
    {
        $leadersModel = new VotersdbLeaders;
        return $this->render('create', [
            'model'     => $leadersModel
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


}


?>
