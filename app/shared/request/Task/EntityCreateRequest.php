<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\request\Task;

use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\task\cases\Entity\handler\CreateResponse;

final class EntityCreateRequest implements BridgeRequest
{
    /**
     * @param non-empty-string $title
     */
    public function __construct(
        public readonly DomainIdentity $identity,
        public readonly string $title,
    ) {
    }

    public function getParams(): array
    {
        return get_object_vars($this);
    }

    public function getHandler(): string
    {
        return CreateResponse::class;
    }
}
