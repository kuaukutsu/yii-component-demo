<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task;

use Psr\Container\ContainerExceptionInterface;
use kuaukutsu\poc\task\service\TaskViewer;
use kuaukutsu\poc\task\tools\NodeServiceFactory;
use kuaukutsu\poc\demo\components\task\node\MainNode;

final readonly class TaskViewerFactory
{
    public function __construct(
        private NodeServiceFactory $factory,
        private MainNode $mainNode,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function createByMain(): TaskViewer
    {
        /**
         * @var TaskViewer
         */
        return $this->factory->factory($this->mainNode, TaskViewer::class);
    }
}
