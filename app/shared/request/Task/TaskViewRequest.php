<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\request\Task;

use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\modules\task\cases\Manage\handler\ViewResponse;

final class TaskViewRequest implements BridgeRequest
{
    /**
     * @param non-empty-string $uuid
     */
    public function __construct(public readonly string $uuid)
    {
    }

    public function getParams(): array
    {
        return get_object_vars($this);
    }

    public function getHandler(): string
    {
        return ViewResponse::class;
    }
}
