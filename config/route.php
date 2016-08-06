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

        //Account Management
        '/<module:account>/<controller:manage>/list'
        => '/<module>/<controller>/index',
        '/<module:account>/<controller:manage>/<action:add>'
        => '/<module>/<controller>/<action>',
        '/<module:account>/<controller:manage>/<action:(view|edit|delete)>/<id:\d+>'
        => '/<module>/<controller>/<action>',
        '/<module:account>/<controller:manage>/<action:check>/<username:.+>'
        => '/<module>/<controller>/<action>',
        // Voters Management
        '/<module:votersmgmt>/<controller:manage>/list'
        => '/<module>/<controller>/index',
        '/<module:votersmgmt>/<controller:manage>/<action:(view|edit)>/<id:\d+>'
        => '/<module>/<controller>/<action>',
        '/<module:votersmgmt>/<controller:manage>/<action:add>'
        => '/<module>/<controller>/<action>',
        '/<module:votersmgmt>/<controller:manage>/<action:delete>/<id:\d+>'
        => '/<module>/<controller>/<action>',
        '/<module:votersmgmt>/<controller:manage>/<action:chart>/<id:(null|\d+)>'
        => '/<module>/<controller>/<action>',
        '/<module:votersmgmt>/<controller:manage>/<action:vote>/<operator:(set|reset)>/<id:\d+>/<user:(voter|leader)>/<leader:\d+>'
        => '/<module>/<controller>/<action>',
        // Leaders Management
        '/<module:leadersmgmt>/<controller:manage>/list'
        => '/<module>/<controller>/index',
        '/<module:leadersmgmt>/<controller:manage>/<action:(delete|edit|memberlist)>/<id:\d+>'
        => '/<module>/<controller>/<action>',
        '/<module:leadersmgmt>/<controller:manage>/<action:(add|getlist)>'
        => '/<module>/<controller>/<action>',
        '/<module:leadersmgmt>/<controller:manage>/<action:leader>/<operator:(appoint|remove)>/<id:\d+>'
        => '/<module>/<controller>/<action>',
        '/<module:leadersmgmt>/<controller:manage>/<action:deletemember>/<member:\d+>/<leader:\d+>'
        => '/<module>/<controller>/<action>',
    ]
];
?>
