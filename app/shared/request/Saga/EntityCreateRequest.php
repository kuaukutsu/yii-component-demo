<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\request\Saga;

use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\handler\CreateResponse;

final readonly class EntityCreateRequest implements BridgeRequest
{
    /**
     * @param array<string, string> $entityData
     * @param non-empty-string[] $tags
     */
    public function __construct(
        public DomainIdentity $identity,
        public array $entityData,
        public array $tags = [],
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
