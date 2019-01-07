<?php
/*$params = array_merge(
    //require __DIR__ . '/../../common/config/params.php',
    //require __DIR__ . '/../../common/config/params-local.php',
    //require __DIR__ . '/params.php',
    //require __DIR__ . '/params-local.php'
);
*/
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'templateFile' => '@jamband/schemadump/template.php',
        ],
        'schemadump' => [
            'class' => jamband\schemadump\SchemaDumpController::class,
            'db' => [
                'class' => yii\db\Connection::class,
                'dsn' => 'mysql:host=localhost;dbname=eulims',
                'username' => 'eulims',
                'password' => 'eulims',
            ],
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            //'class' => 'common\models\User',
            //'enableAutoLogin' => true,
            //'loginUrl' => ['admin/user/login'],
        ],
    ],
    //'params' => $params,
];
