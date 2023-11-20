<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\step\Step;
use kuaukutsu\poc\saga\step\StepCollection;
use kuaukutsu\poc\saga\TransactionInterface;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;

final class CreateTransaction implements TransactionInterface
{
    /**
     * @param array<string, scalar> $entityData
     * @param non-empty-string[] $tags
     */
    public function __construct(
        private readonly DomainIdentity $identity,
        private readonly array $entityData,
        private readonly array $tags,
    ) {
    }

    public function steps(): StepCollection
    {
        return new StepCollection(
            new Step(
                EntityCreate::class,
                [
                    'identity' => $this->identity,
                    'data' => $this->entityData,
                ]
            ),
            new Step(
                TagCreate::class,
                [
                    'identity' => $this->identity,
                    'tags' => $this->tags,
                ]
            ),
            new Step(
                EntityTagMap::class,
                [
                    'identity' => $this->identity,
                    'tags' => $this->tags,
                ]
            ),
        );
    }
}
