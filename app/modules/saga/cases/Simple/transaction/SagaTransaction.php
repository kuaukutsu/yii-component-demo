<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Simple\transaction;

use kuaukutsu\poc\saga\step\Step;
use kuaukutsu\poc\saga\step\StepCollection;
use kuaukutsu\poc\saga\TransactionInterface;

final class SagaTransaction implements TransactionInterface
{
    public function __construct(
        private readonly string $comment,
    ) {
    }

    public function steps(): StepCollection
    {
        return new StepCollection(
            new Step(
                SagaCreate::class,
                [
                    'comment' => $this->comment,
                ]
            ),
            new Step(
                SagaModify::class,
                []
            ),
        );
    }
}
