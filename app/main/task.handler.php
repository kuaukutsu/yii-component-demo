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

use kuaukutsu\poc\task\handler\StageHandler;

use function kuaukutsu\poc\task\tools\get_previous_uuid;
use function kuaukutsu\poc\task\tools\get_stage_uuid;

$dirname = dirname(__DIR__);

require $dirname . '/vendor/autoload.php';
require $dirname . '/config/bootstrap.php';
require $dirname . '/vendor/yiisoft/yii2/Yii.php';

/** @noinspection PhpUnhandledExceptionInspection */
new yii\console\Application(
    require $dirname . '/config/console.task.php'
);

/**
 * @psalm-var StageHandler $handler
 * @noinspection PhpUnhandledExceptionInspection
 */
$handler = Yii::$container->get(StageHandler::class);
$exitCode = $handler->handle(
    get_stage_uuid(),
    get_previous_uuid(),
);
exit($exitCode);
