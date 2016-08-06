<?php

namespace app\modules\account\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;
use app\models\LoginForm;
use app\models\VotersdbLeaders;

class SessionController extends \yii\web\Controller
{
    public $layout = '/loginLayout';
    public $route_nav;
    public $viewPath = 'app/modules/account/views';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // Pages that are included in the rule set
                'only'  => ['index', 'login', 'logout', 'dashboard'],
                'rules' => [
                    [ // Pages that can be accessed without logging in
                        'allow'     => true,
                        'actions'   => ['login'],
                        'roles'     => ['?']
                    ],
                    [ // Pages that can be accessed when logged in
                        'allow'     => true,
                        'actions'   => ['logout', 'dashboard'],
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

    public function actionLogin()
    {
        $model = new LoginForm;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = Yii::$app->user->getIdentity()->attributes;
            $session = Yii::$app->session;
            $session->open();
            foreach ($user as $userKey => $userValue) {
                $session[$userKey] = $userValue;
            }
            $session->close();
        }

        return $this->render('login', [
            'model' => $model
        ]);
    }

    public function actionLogout()
    {
        $session = Yii::$app->session;
        $session->removeAll();          // Removes all the session variables
        $session->destroy();            // Destroy session
        Yii::$app->response->clear();   // Clears the headers, cookies, content, status code of the response.
        Yii::$app->user->logout();
        $this->goHome();
    }

    public function actionDashboard()
    {
        $this->layout = '/commonLayout';
        $model = new VotersdbLeaders;
        $rows = $model->getSummaryBLeaders();
        $leaders = ['0' => 'All'];
        $records = [];

        if(!empty($rows)) {
            $leaders =  $leaders + $rows['leaders'];
            $records = $rows['records'];
        }

        return $this->render('dashboard', [
            'records' => $records,
            'leaders' => $leaders
        ]);
    }

}

?>
