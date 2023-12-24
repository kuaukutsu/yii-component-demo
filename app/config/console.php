<?php

declare(strict_types=1);

use yii\console\Request;
use yii\console\Response;
use yii\console\controllers\MigrateController;
use kuaukutsu\poc\demo\components\AppBuild;
use kuaukutsu\poc\demo\modules\command\CommandBootstrap;

$config = [
    'id' => 'console',
    'controllerNamespace' => 'app\modules\command',
    // implements BootstrapInterface
    'bootstrap' => [
        CommandBootstrap::class,
    ],
    // console components
    'components' => [
        'request' => [
            'class' => Request::class,
        ],
        'response' => [
            'class' => Response::class,
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => [
                '@yii/rbac/migrations',
                '@app/components/task/migrations',
                '@app/modules/saga/migrations',
            ],
        ],
    ],
];

if (file_exists(__DIR__ . '/console.local.php')) {
    $config = array_merge_recursive($config, require __DIR__ . '/console.local.php');
}

return (new AppBuild($config, __DIR__, AppBuild::LAYER_CONSOLE))->buildConfig();
