<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\node;

use Psr\Container\ContainerInterface;
use kuaukutsu\poc\task\EntityNode;
use kuaukutsu\poc\task\service\StageCommand;
use kuaukutsu\poc\task\service\StageQuery;
use kuaukutsu\poc\task\service\TaskCommand;
use kuaukutsu\poc\task\service\TaskQuery;
use kuaukutsu\poc\demo\components\task\node\main\MainStageSearch;
use kuaukutsu\poc\demo\components\task\node\main\MainStageService;
use kuaukutsu\poc\demo\components\task\node\main\MainTaskSearch;
use kuaukutsu\poc\demo\components\task\node\main\MainTaskService;

final class MainNode implements EntityNode
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function label(): string
    {
        return 'main';
    }

    public function getTaskQuery(): TaskQuery
    {
        /**
         * @var MainTaskSearch
         */
        return $this->container->get(MainTaskSearch::class);
    }

    public function getTaskCommand(): TaskCommand
    {
        /**
         * @var MainTaskService
         */
        return $this->container->get(MainTaskService::class);
    }

    public function getStageQuery(): StageQuery
    {
        /**
         * @var MainStageSearch
         */
        return $this->container->get(MainStageSearch::class);
    }

    public function getStageCommand(): StageCommand
    {
        /**
         * @var MainStageService
         */
        return $this->container->get(MainStageService::class);
    }
}
