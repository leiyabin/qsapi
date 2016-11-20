<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id'         => 'basic',
    'language'   => 'zh-CN',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => ['log'],
    'components' => [
        'request'       => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'bdZT_oJ1cjlVep23SLGkoAUASxJS1Mgk',
            'parsers'             => [
                'application/json' => 'yii\web\JsonParser',
                'text/json'        => 'yii\web\JsonParser',
            ]
        ],
        'response'      => [
            'class'         => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $response->statusCode = 200;
            },
        ],
        'cache'         => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler'  => [
            'errorAction' => 'site/error',
        ],
        'log'           => require(__DIR__ . '/log.php'),
        'db'            => require(__DIR__ . '/db.php'),
        'urlManager'    => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
            ],
        ],
    ],
    'params'     => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
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
