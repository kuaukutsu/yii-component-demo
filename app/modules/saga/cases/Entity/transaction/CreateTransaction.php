<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\step\Step;
use kuaukutsu\poc\saga\step\StepCollection;
use kuaukutsu\poc\saga\TransactionInterface;

final class CreateTransaction implements TransactionInterface
{
    public function __construct(
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
                    'data' => $this->entityData,
                ]
            ),
            new Step(
                TagCreate::class,
                [
                    'tags' => $this->tags,
                ]
            ),
            new Step(
                EntityTagMap::class,
                []
            ),
        );
    }
}
