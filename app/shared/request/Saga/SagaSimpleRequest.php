<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\request\Saga;

use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\saga\cases\Simple\handler\CreateResponse;

final class SagaSimpleRequest implements BridgeRequest
{
    public function __construct(
        public readonly DomainIdentity $identity,
        public readonly string $comment,
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
