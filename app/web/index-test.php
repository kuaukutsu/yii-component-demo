<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../config/bootstrap.tests.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/test.php';

/**
 * @psalm-suppress UncaughtThrowInGlobalScope
 * @noinspection PhpUnhandledExceptionInspection
 */
(new yii\web\Application($config))->run();
