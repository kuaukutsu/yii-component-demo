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

use Ramsey\Uuid\Uuid;
use kuaukutsu\poc\task\handler\StageHandler;

use function kuaukutsu\poc\demo\argument;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/bootstrap.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

/** @noinspection PhpUnhandledExceptionInspection */
new yii\console\Application(
    require __DIR__ . '/../config/console.task.php'
);

/** @var non-empty-string|null $stageUuid */
$stageUuid = argument('stage');
/** @var non-empty-string|null $previousUuid */
$previousUuid = argument('previous');
if ($stageUuid === null || Uuid::isValid($stageUuid) === false) {
    throw new RuntimeException("Stage UUID must be declared.");
}

/**
 * @psalm-var StageHandler $handler
 * @noinspection PhpUnhandledExceptionInspection
 */
$handler = Yii::$container->get(StageHandler::class);
$handler->handle($stageUuid, $previousUuid);
