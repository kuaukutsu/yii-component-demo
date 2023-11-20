<?php

declare(strict_types=1);

use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;
use yii\caching\CacheInterface;
use yii\di\Container;
use yii\mail\MailerInterface;
use yii\rbac\ManagerInterface;
use kuaukutsu\poc\demo\components\security\SecurityDecorator;
use kuaukutsu\poc\demo\components\security\SecurityInterface;

$container = [
    'resolveArrays' => true,
    'singletons' => [
        Container::class => static fn() => Yii::$container,
        CacheInterface::class => static fn() => Yii::$app->getCache(),
        ManagerInterface::class => static fn() => Yii::$app->getAuthManager(),
        MailerInterface::class => static fn() => Yii::$app->getMailer(),
        // container
        \DI\Container::class => new \DI\Container(
            [
                SecurityInterface::class => \DI\create(SecurityDecorator::class),
                UuidFactoryInterface::class => \DI\create(UuidFactory::class),
            ]
        ),
    ],
    'definitions' => [
        SecurityInterface::class => SecurityDecorator::class,
        UuidFactoryInterface::class => UuidFactory::class,

    ],
];

return $container;
