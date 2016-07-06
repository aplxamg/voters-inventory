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
        => '/<module>/<controller>/index',
        '/<module:votersmgmt>/<controller:manage>/<action:(view|edit)>/<id:\d+>'
        => '/<module>/<controller>/<action>',
        '/<module:votersmgmt>/<controller:manage>/<action:add>'
        => '/<module>/<controller>/<action>',
        '/<module:votersmgmt>/<controller:manage>/<action:delete>/<id:\d+>'
        => '/<module>/<controller>/<action>',
        // Leaders Management
        '/<module:leadersmgmt>/<controller:manage>/list'
        => '/<module>/<controller>/index',
        '/<module:leadersmgmt>/<controller:manage>/<action:delete>/<id:\d+>'
        => '/<module>/<controller>/<action>',
    ]
];
?>
