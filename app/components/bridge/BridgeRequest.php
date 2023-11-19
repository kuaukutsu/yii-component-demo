<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\bridge;

interface BridgeRequest
{
    /**
     * @return array<array-key, mixed>|array<empty>
     */
    public function getParams(): array;

    /**
     * @return class-string<BridgeResponse>
     */
    public function getHandler(): string;
}
