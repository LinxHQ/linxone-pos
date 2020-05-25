<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'cdLtE7gL4EBy2ibV7oHnT4VXKOpUuAWL',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
//          'view' => [
//            'theme' => require(__DIR__ . '/theme.php')
//        ],
        
//        'view' => [
//            'theme' => [
//               'basePath' => '@webroot/themes/default',
//               'baseUrl' => '@web/themes/default',
//               'pathMap' => [
//                  '@app/views' => ['@webroot/themes/default/views',  
//                          
//                  ],
//                   "@app/views" =>"@webroot/themes/park_new/views",
//                        "@app/modules"=>"@webroot/themes/park_new/modules",
//               ]
//            ],
//         ],
    ],
    'modules' => [
         'hello' => [
            'class' => 'app\modules\hello\Hello', 
         ],
        'pos' => [
            'class' => 'app\modules\pos\Pos', 
         ],
      ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
     $config['modules']['members'] = ['class' => '\app\modules\members\memberModule'];
    $config['modules']['membership_type'] = ['class' => '\app\modules\membership_type\membershipTypeModule'];
    $config['modules']['invoice'] = ['class' => '\app\modules\invoice\invoiceModule'];
    $config['modules']['report'] = ['class' => '\app\modules\report\reportModule'];
    $config['modules']['training'] = ['class' => '\app\modules\training\trainingModule'];
    $config['modules']['checkin'] = ['class' => '\app\modules\checkin\Module'];
    $config['modules']['facility'] = ['class' => '\app\modules\facility\Module'];
    $config['modules']['booking'] = ['class' => '\app\modules\booking\Module'];
    $config['modules']['history'] = ['class' => '\app\modules\history\Module'];
    $config['modules']['permission'] = ['class' => '\app\modules\permission\Module'];
    $config['modules']['trainer'] = ['class' => '\app\modules\trainer\trainerModule'];
    $config['modules']['trainer_booking'] = ['class' => '\app\modules\trainer_booking\Module'];
    $config['modules']['crm'] = ['class' => '\app\modules\crm\Module'];
    $config['modules']['revenue_type'] = ['class' => '\app\modules\revenue_type\Module'];
	$config['modules']['sale'] = ['class' => '\app\modules\sale\Module'];
	$config['modules']['pos'] = ['class' => '\app\modules\pos\Module'];
	$config['modules']['course'] = ['class' => '\app\modules\course\Module'];
	$config['modules']['event'] = ['class' => '\app\modules\event\Module'];
	$config['modules']['checkin_entity'] = ['class' => '\app\modules\checkin_entity\Module'];
	$config['modules']['comment'] = ['class' => 'app\modules\comment\Module'];
    $config['modules']['feedback'] = ['class' => 'app\modules\feedback\Module'];
}

return $config;
