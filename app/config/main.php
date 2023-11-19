<?php

declare(strict_types=1);

use yii\redis\Cache;
use yii\web\User;

return [
    'name' => 'main',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru',
    'aliases' => [
        '@tests' => '@app/tests',
    ],
    // implements BootstrapInterface
    'bootstrap' => [],
    // common modules
    'modules' => require __DIR__ . '/modules.php',
    // common dependencies
    'container' => require __DIR__ . '/container.php',
    // common components
    'components' => [
        'cache' => [
            'class' => Cache::class,
        ],
        'user' => [
            'class' => User::class,
            'autoRenewCookie' => false,
            'enableSession' => false,
        ],
        'formatter' => [
            'defaultTimeZone' => 'UTC',
        ],
    ],
    'params' => require __DIR__ . '/params.php',
];
