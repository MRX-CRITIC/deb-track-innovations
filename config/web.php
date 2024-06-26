<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'defaultRoute' => '/site/index',
    'name' => 'DebTrack Innovations',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
//    'modules' => [
//        'user' => [
//            'class' => 'app\modules\user\Modele',
//        ],
//    ],
    'components' => [

        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'jxNP40EZ-bjinchiVQvyCeIU5-kqeUKO',
            'csrfCookie' => [
                'httpOnly' => true,
            ],
            'baseUrl' => '',
            'enableCsrfValidation' => false,
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->headers->set('X-Content-Type-Options', 'nos niff');
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\entity\Users',
            'enableAutoLogin' => true,
            'autoRenewCookie' => true,
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'secure' => false
            ],
            'loginUrl' => ['user/login']
        ],
        'session' => [
//            'session' => ['class' => 'yii\web\Session'], //для веб приложений
            'class' => 'yii\web\Session',
            'cookieParams' => [
                'lifetime' => 240 * 3600, // 24 часа
                'httpOnly' => true,
                'path' => '/',
//                'secure' => true,
            ],
            'timeout' => 240 * 3600, // 24 часа, также настройка для session.gc_maxlifetime
            'useCookies' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.beget.com', //smtp.beget.com //smtp.gmail.com
                'username' => 'info@deb-track-innovations.ru',
                'password' => 'C2eeLP0%',
                'port' => '2525', //465 2525
                'encryption' => 'tls', //ssl
            ],
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => false,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru-RU',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ', // Разделитель тысяч
            'currencyCode' => 'RUB',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'], // 'info'
//                    'logFile' => '@runtime/logs/app.log',
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
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
