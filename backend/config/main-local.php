<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '8CMwBcOKnDDrSRmsOGHnYE1TFxlbcaX5',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'name' => 'advanced-backend',
            'cookieParams' => [
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => false,
                'httpOnly' => true,
            ],
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    // Temporarily disabled debug module due to session configuration issue
    // $config['bootstrap'][] = 'debug';
    // $config['modules']['debug'] = [
    //     'class' => 'yii\debug\Module',
    // ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
