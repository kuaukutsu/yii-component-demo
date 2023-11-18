<?php

declare(strict_types=1);

use yii\gii\Module as GiiModule;
use yii\debug\Module as DebugModule;

return [
    'gii' => [
        'class' => GiiModule::class,
        'allowedIPs' => ['*'],
    ],
    'debug' => [
        'class' => DebugModule::class,
        'historySize' => 500,
        'allowedIPs' => ['*'],
        'disableIpRestrictionWarning' => true,
    ],
];
