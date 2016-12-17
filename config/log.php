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
            'logVars'    => [],
            'levels'     => ['error', 'info', 'trace'],
            'categories' => ['application', 'request', 'response'],
            'logFile'    => '@app/runtime/logs/app.all.log',
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['info'],
            'categories' => ['request'],
            'logFile'    => '@app/runtime/logs/request.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['error'],
            'categories' => ['request'],
            'logFile'    => '@app/runtime/logs/request.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['info'],
            'categories' => ['response'],
            'logFile'    => '@app/runtime/logs/response.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['error'],
            'categories' => ['response'],
            'logFile'    => '@app/runtime/logs/response.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['error'],
            'categories' => ['rpc'],
            'logFile'    => '@app/runtime/logs/rpc.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['info'],
            'categories' => ['rpc'],
            'logFile'    => '@app/runtime/logs/rpc.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['info'],
            'categories' => ['application'],
            'logFile'    => '@app/runtime/logs/app.info.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['error'],
            'categories' => ['application'],
            'logFile'    => '@app/runtime/logs/app.error.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['trace'],
            'categories' => ['sql'],
            'logFile'    => '@app/runtime/logs/sql.log'
        ],
        [
            'class'      => 'yii\log\FileTarget',
            'logVars'    => [],
            'levels'     => ['error', 'info'],
            'categories' => ['rpc'],
            'logFile'    => '@app/runtime/logs/rpc.log'
        ]
    ],
];