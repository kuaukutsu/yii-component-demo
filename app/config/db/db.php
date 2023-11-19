<?php

declare(strict_types=1);

use yii\db\Connection;

$env = getenv();

/**
 * @psalm-suppress TypeDoesNotContainType
 */
return [
    'class' => Connection::class,
    'dsn' => $env['YII_DB_DSN'] ?? '',
    'username' => $env['YII_DB_USERNAME'] ?? '',
    'password' => $env['YII_DB_PASSWORD'] ?? '',
    'charset' => $env['YII_DB_CHARSET'] ?? 'utf8',
    // Schema cache options (for production environment)
    'enableSchemaCache' => false,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
