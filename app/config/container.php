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
        SecurityInterface::class => SecurityDecorator::class,
    ],
    'definitions' => [
        UuidFactoryInterface::class => UuidFactory::class,
        \DI\Container::class => new \DI\Container(
            [
                UuidFactoryInterface::class => \DI\create(UuidFactory::class),
            ]
        ),
    ],
];

return $container;
