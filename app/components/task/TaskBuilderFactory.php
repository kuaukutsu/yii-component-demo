<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task;

use Psr\Container\ContainerExceptionInterface;
use kuaukutsu\poc\task\tools\NodeServiceFactory;
use kuaukutsu\poc\task\service\TaskCreator;
use kuaukutsu\poc\task\TaskBuilder;
use kuaukutsu\poc\demo\components\task\node\MainNode;

final readonly class TaskBuilderFactory
{
    public function __construct(
        private NodeServiceFactory $factory,
        private MainNode $mainNode,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function createByMain(): TaskBuilder
    {
        /** @var TaskCreator $creator */
        $creator = $this->factory->factory($this->mainNode, TaskCreator::class);
        return new TaskBuilder($creator);
    }
}
