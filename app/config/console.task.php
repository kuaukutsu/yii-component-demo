<?php

declare(strict_types=1);

use kuaukutsu\poc\task\service\StageCommand;
use kuaukutsu\poc\task\service\StageQuery;
use kuaukutsu\poc\task\service\TaskCommand;
use kuaukutsu\poc\task\service\TaskQuery;
use kuaukutsu\poc\demo\components\task\node\main\MainStageSearch;
use kuaukutsu\poc\demo\components\task\node\main\MainStageService;
use kuaukutsu\poc\demo\components\task\node\main\MainTaskSearch;
use kuaukutsu\poc\demo\components\task\node\main\MainTaskService;

$config = require __DIR__ . '/console.php';
$config['id'] = 'task';
$config['requestedRoute'] = 'task';

/**
 * Коррекция logger.
 * Чаще сбрасываем накопленные логи на диск, иначе flush не отрабатывает.
 * (exported when the application terminates)
 */
if (isset($config['components']['log'])) {
    $config['components']['log']['flushInterval'] = 1;
}

$config['container']['definitions'] = array_merge(
    $config['container']['definitions'],
    [
        TaskCommand::class => MainTaskService::class,
        TaskQuery::class => MainTaskSearch::class,
        StageCommand::class => MainStageService::class,
        StageQuery::class => MainStageSearch::class,
    ]
);

return $config;
