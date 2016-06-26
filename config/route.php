<?php

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => true,
    'rules' => [
        /* Automatic referencing for urls <module>/controller/action format */
        ''
        => '/account/session/login',
        '/<action:(login|logout|dashboard)>'
        => '/account/session/<action>',
        // Voters Management
        '/<module:votersmgmt>/<controller:manage>/list'
        => '/<module>/<controller>/index'

    ]
];
?>