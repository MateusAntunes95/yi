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
            'cookieValidationKey' => 'o6orU7N6qhp2Hs8uEynbhs8PGSCSSuQh',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
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

        // ⭐ URLS DA API
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [

                // --- CORS preflight ---
                '<controller:\w+>/<action:\w+>' => 'options',

                // --- Club ---
                'club/detail/<slug:[\w-]+>/<field:[\w-]+>' => 'club/detail',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'club', 'pluralize' => false],

                // --- Generic Crawler ---
                'generic/extract' => 'generic/extract',
            ],
        ],

        // ⭐ CORS para React
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $headers = $response->headers;

                $headers->set('Access-Control-Allow-Origin', '*');
                $headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
                $headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
            },
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
