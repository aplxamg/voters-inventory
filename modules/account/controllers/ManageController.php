<?php

namespace app\modules\account\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\helpers\Data;
use app\models\User;

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
        $userModel = new User;
        $params = ['status' => 'active','user_type' => ['encoder','leader']];
        $records = Data::findRecords($userModel, null, $params, 'all');
         return $this->render('list', [
            'records'       => $records
        ]);
    }

    public function actionAdd()
    {
        $userModel = new User;
        $errorMsg   = 0;
        if(Yii::$app->request->isPost) {
            $values = Yii::$app->request->post('User');
            $params = [ 'status' => 'active', 'username' => $values['username']];
            $record = Data::findRecords($userModel, null, $params);
            if(count($record) == 0) {
                if($userModel->saveAccount($userModel,$values)) {
                    return $this->redirect('/account/manage/list');
                } else {
                    $errorMsg = 1;
                }
            } else {
                $errorMsg = 2;
            }
        }
        return $this->render('create', [
            'model' => $userModel,
            'error' => $errorMsg
        ]);
    }
}


?>
