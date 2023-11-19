<?php

declare(strict_types=1);

$config = require __DIR__ . '/console.php';
$config['id'] = 'scheduler';
$config['requestedRoute'] = 'scheduler';

/**
 * Коррекция logger.
 * Чаще сбрасываем накопленные логи на диск, иначе flush не отрабатывает.
 * (exported when the application terminates)
 */
if (isset($config['components']['log'])) {
    $config['components']['log']['flushInterval'] = 1;
}

return $config;
