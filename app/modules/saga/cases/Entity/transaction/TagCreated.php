<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\TransactionDataInterface;

final class TagCreated implements TransactionDataInterface
{
    /**
     * @param non-empty-string[] $tags
     */
    public function __construct(public readonly array $tags)
    {
    }

    public function toArray(): array
    {
        return $this->tags;
    }
}
