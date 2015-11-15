<?php
return [
    'language' => 'ru_RU',
    'charset' => 'UTF-8',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Yekaterinburg',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'authClientCollection' => require(__DIR__ . '/auth.php'),
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            //'forceCopy' => true,                                  // каждый раз чистит assets
        ],
        'formatter' => [                                            // выводит данные в заданом формате
            //'dateFormat' => 'full',                               // объем информации о дате (short, medium, long, full)
            //'dateFormat' => 'dd-MM-yyyy',                         // ICU format
            'dateFormat' => 'php:d mm Y',                           // PHP date()-format
            'datetimeFormat' => 'full',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',
        ],
    ],
];
