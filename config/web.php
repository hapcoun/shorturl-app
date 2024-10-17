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
        'queue' => [
            'class' => \yii\queue\Queue::class,
            'redis' => 'redis',
            'channel' => 'queue',
            'as log' => \yii\queue\LogBehavior::class,
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 0,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TWVdvY0l2hsCpVfzvDS7szrvlCj5GeFK',
        ],
        'cache' => [
            'class' => \yii\redis\Cache::class,
            'redis' => [
                'hostname' => 'redis',
                'port' => 6379,
                'database' => 0,
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<code:[A-Za-z0-9_-]{1,5}>' => 'shorturl/redirect',
                '' => 'shorturl/index',
                'app/create' => 'shorturl/create',
                'app/statistics/<id:\d+>' => 'shorturl/statistics',
//                'app/stats' => 'shorturl/stats',
                'app/login' => 'site/login',
                'app/logout' => 'site/logout',
                'app/signup' => 'site/signup',
            ],
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
}

return $config;
