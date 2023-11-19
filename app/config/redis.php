<?php

declare(strict_types=1);

use yii\helpers\Inflector;
use yii\redis\Connection as RedisConnection;
use yii\redis\Mutex as RedisMutex;

$env = getenv();

$fnTemplate = static function (string $key) use ($env): array {
    $fnPrepareKey = static fn(string $key, string $postfix): string => strtoupper(
        sprintf('YII_%s_%s', Inflector::camel2id($key, '_'), $postfix)
    );

    return [
        'class' => RedisConnection::class,
        'retries' => 1,
        'retryInterval' => 5_000_000,
        'hostname' => $env[$fnPrepareKey($key, 'host')] ?? $env['YII_REDIS_HOST'] ?? null,
        'port' => $env[$fnPrepareKey($key, 'port')] ?? $env['YII_REDIS_PORT'] ?? null,
        'database' => $env[$fnPrepareKey($key, 'database')] ?? $env['YII_REDIS_DATABASE'] ?? null,
        'password' => $env[$fnPrepareKey($key, 'password')] ?? $env['YII_REDIS_PASSWORD'] ?? null,
    ];
};

return [
    'redis' => $fnTemplate('redis'),
    'mutex' => [
        'class' => RedisMutex::class,
        'redis' => 'redis',
    ],
];
