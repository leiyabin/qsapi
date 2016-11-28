<?php
/**
 * Created by PhpStorm.
 * User: lyb
 * Date: 2016/11/13
 * Time: 10:36
 */
return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets'    => [
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['error', 'info'],
            'categories' => ['application', 'request', 'response', 'rpc', 'db'],
            'logFile'    => '@app/runtime/logs/app.all.log',
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['info'],
            'categories' => ['request'],
            'logFile'    => '@app/runtime/logs/request.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['error'],
            'categories' => ['request'],
            'logFile'    => '@app/runtime/logs/request.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['info'],
            'categories' => ['response'],
            'logFile'    => '@app/runtime/logs/response.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['error'],
            'categories' => ['response'],
            'logFile'    => '@app/runtime/logs/response.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['error'],
            'categories' => ['rpc'],
            'logFile'    => '@app/runtime/logs/rpc.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['info'],
            'categories' => ['rpc'],
            'logFile'    => '@app/runtime/logs/rpc.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['info'],
            'categories' => ['application'],
            'logFile'    => '@app/runtime/logs/app.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['error'],
            'categories' => ['application'],
            'logFile'    => '@app/runtime/logs/app.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'levels'     => ['info'],
            'categories' => ['db'],
            'logFile'    => '@app/runtime/logs/app.db.error.log'
        ]
    ],
];