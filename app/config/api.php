<?php

declare(strict_types=1);

use yii\web\JsonParser;
use yii\web\Request;
use yii\web\Response;
use yii\web\UrlManager;
use kuaukutsu\poc\demo\components\AppBuild;

$config = [
    'id' => 'api',
    'aliases' => [
        '@npm' => '@vendor/npm-asset',
        '@bower' => '@vendor/bower-asset',
    ],
    // implements BootstrapInterface
    'bootstrap' => [],
    'components' => [
        'request' => [
            'class' => Request::class,
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'response' => [
            'class' => Response::class,
            'format' => Response::FORMAT_JSON,
        ],
        'urlManager' => [
            'class' => UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require __DIR__ . '/rules.php',
        ],
    ],
];

if (file_exists(__DIR__ . '/api.local.php')) {
    $config = array_merge_recursive($config, require __DIR__ . '/api.local.php');
}

return (new AppBuild($config, __DIR__))->buildConfig();
