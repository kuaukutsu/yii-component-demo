<?php

declare(strict_types=1);

use function kuaukutsu\poc\demo\boolean;
use function kuaukutsu\poc\demo\getenvironment;

// dev, prod, test
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('YII_DEBUG') or define('YII_DEBUG', boolean(getenvironment('YII_DEBUG', true)));
// development, production, staging
defined('APP_ENV_TEST') or define('APP_ENV_TEST', false);
defined('APP_ENV_DEVEL') or define('APP_ENV_DEVEL', true);
defined('APP_API_URL') or define('APP_API_URL', getenvironment('APP_API_URL', 'http://localhost'));
defined('APP_BASE_URL') or define('APP_BASE_URL', getenvironment('APP_BASE_URL', 'http://localhost'));
