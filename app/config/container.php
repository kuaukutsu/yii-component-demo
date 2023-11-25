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
use kuaukutsu\poc\demo\modules\task\service\StageSearch;
use kuaukutsu\poc\demo\modules\task\service\StageService;
use kuaukutsu\poc\demo\modules\task\service\TaskSearch;
use kuaukutsu\poc\demo\modules\task\service\TaskService;
use kuaukutsu\poc\task\service\StageCommand;
use kuaukutsu\poc\task\service\StageQuery;
use kuaukutsu\poc\task\service\TaskCommand;
use kuaukutsu\poc\task\service\TaskQuery;

use function DI\create;

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
                SecurityInterface::class => create(SecurityDecorator::class),
                UuidFactoryInterface::class => create(UuidFactory::class),
                TaskCommand::class => create(TaskService::class),
                TaskQuery::class => create(TaskSearch::class),
                StageCommand::class => create(StageService::class),
                StageQuery::class => create(StageSearch::class),
            ]
        ),
    ],
    'definitions' => [
        SecurityInterface::class => SecurityDecorator::class,
        UuidFactoryInterface::class => UuidFactory::class,
        TaskCommand::class => TaskService::class,
        TaskQuery::class => TaskSearch::class,
        StageCommand::class => StageService::class,
        StageQuery::class => StageSearch::class,
    ],
];

return $container;
