<?php
return [
    require_once(__DIR__ . '/../../common/config/functions.php'),       // файл для функций дебага d() и dd()

    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'secretKeyExpire' => 60 * 60,                       // время хранения ключа
    'emailActivation' => false,                         // активация через емайл
    'loginWithEmail' => true,                           // вход через емайл
];
