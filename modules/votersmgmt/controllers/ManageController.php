<?php

namespace app\modules\votersmgmt\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Data;
use app\models\VotersdbVoters;

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
        $votersModel = new VotersdbVoters;
        $params = ['status' => 'active'];
        $records = Data::findRecords($votersModel, null, $params, 'all');
        return $this->render('list', [
            'records'       => $records
        ]);
    }

    public function actionAdd()
    {
        $votersModel = new VotersdbVoters;
        $errorMsg   = 0;
        if(Yii::$app->request->isPost) {
            if(Yii::$app->request->post('save')) {
                $event = 'list';
            } else {
                $event = 'create';
            }

            $values = Yii::$app->request->post('VotersdbVoters');
            if($votersModel->saveVoter($votersModel, null, $values)) {
                return $this->redirect('/votersmgmt/manage/'.$event);
            } else {
                $errorMsg = 1;
            }
        }

        return $this->render('create', [
            'model' => $votersModel,
            'error' => $errorMsg
        ]);
    }

}


?>
