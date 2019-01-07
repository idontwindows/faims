<?php
use kartik\mpdf\Pdf;

return [
    'mailer' => [
           'class' => 'yii\swiftmailer\Mailer',
           'viewPath' => '@common/mail',
            'useFileTransport' => false,//set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'paymentonelab@gmail.com',
                'password' => 'Arkem@88',
                'port' => '587',
                'encryption' => 'tls',
                'streamOptions'=>[
                   'ssl'=>[
                        'verify_peer'=>false,
                        'verify_peer_name'=>false,
                        'allow_self_signed'=>true
                  ]
                ]
            ],
    ],
    'cache' => [
            'class' => 'yii\caching\FileCache',
    ],
    'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'defaultRoles' => ['Guest'],
    ],
    'user' => [
            //'identityClass' => 'mdm\admin\models\User',
            //'class'=>'mdm\admin\models\User',
            //'loginUrl' => ['admin/user/login'],
            'identityClass' => 'common\models\User',
            //'class'=>'common\models\User',
    ],
    'pdf' => [
        'class' => Pdf::classname(),
        'format' => Pdf::FORMAT_A4,
        'orientation' => Pdf::ORIENT_PORTRAIT,
        'destination' => Pdf::DEST_BROWSER,
        // refer settings section for all configuration options
    ]
];
 