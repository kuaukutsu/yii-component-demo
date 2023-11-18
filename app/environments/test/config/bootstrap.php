<?php

declare(strict_types=1);

function filterBoolean(bool|int|string $value): bool
{
    if (is_bool($value)) {
        return $value;
    }

    return match ((string)$value) {
        '1', 'true' => true,
        default => false,
    };
}

function getValue(bool|int|string $value, bool|int|string $default): bool|int|string
{
    return $value === false ? $default : $value;
}

// dev, prod, test
defined('YII_ENV') or define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', false);
// development, production, staging
defined('APP_ENV_TEST') or define('APP_ENV_TEST', true);
defined('APP_ENV_DEVEL') or define('APP_ENV_DEVEL', false);
defined('APP_API_URL') or define('APP_API_URL', getValue(getenv('APP_API_URL'), 'http://localhost'));
defined('APP_BASE_URL') or define('APP_BASE_URL', getValue(getenv('APP_BASE_URL'), 'http://localhost'));
