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

use kuaukutsu\poc\cron\Scheduler;
use kuaukutsu\poc\cron\SchedulerCommand;
use kuaukutsu\poc\cron\SchedulerOptions;
use kuaukutsu\poc\cron\SchedulerTimer;
use kuaukutsu\poc\cron\tools\SchedulerOutput;
use kuaukutsu\poc\demo\modules\command\cases\Crontab\DatetimeCrontab;
use kuaukutsu\poc\demo\modules\command\components\scheduler\SchedulerLogger;
use kuaukutsu\poc\demo\modules\command\components\scheduler\SchedulerTrace;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/bootstrap.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

/** @noinspection PhpUnhandledExceptionInspection */
new yii\console\Application(
    require __DIR__ . '/../config/console.scheduler.php'
);

$scheduler = new Scheduler(
    new SchedulerCommand(
        new DatetimeCrontab(),
        SchedulerTimer::everyMinute()
    ),
);

$scheduler->on(new SchedulerOutput());
$scheduler->on(Yii::$container->get(SchedulerLogger::class));
$scheduler->on(Yii::$container->get(SchedulerTrace::class));

/** @noinspection PhpUnhandledExceptionInspection */
$scheduler->run(
    new SchedulerOptions(
        keeperInterval: 2,
        timeout: 3600,
    )
);
