#!/usr/bin/env php
<?php

/**
 * Yii console bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

declare(strict_types=1);

use kuaukutsu\poc\task\TaskManager;
use kuaukutsu\poc\task\TaskManagerOptions;
use kuaukutsu\poc\task\tools\TaskManagerOutput;

require __DIR__ . '/../config/bootstrap.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

/** @noinspection PhpUnhandledExceptionInspection */
new yii\console\Application(
    require __DIR__ . '/../config/console.task.php'
);

/**
 * @var TaskManager $manager
 * @noinspection PhpUnhandledExceptionInspection
 */
$manager = Yii::$container->get(TaskManager::class);
$manager->on(new TaskManagerOutput());
/** @noinspection PhpUnhandledExceptionInspection */
$manager->run(
    new TaskManagerOptions(
        bindir: __DIR__,
        heartbeat: 5.,
        keeperInterval: 1.,
        handlerEndpoint: 'task.handler.php'
    )
);
