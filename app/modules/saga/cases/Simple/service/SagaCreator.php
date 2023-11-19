<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Simple\service;

use kuaukutsu\poc\saga\TransactionRunner;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\modules\saga\cases\Simple\transaction\SagaCreate;
use kuaukutsu\poc\demo\modules\saga\cases\Simple\transaction\SagaTransaction;
use kuaukutsu\poc\demo\modules\saga\models\SagaDto;

final class SagaCreator
{
    public function __construct(
        private readonly TransactionRunner $transaction,
    ) {
    }

    /**
     * @throws ModelDeleteException
     */
    public function create(DomainIdentity $identity, string $comment): SagaDto
    {
        $transaction = $this->transaction->run(
            new SagaTransaction($comment)
        );

        /**
         * @var SagaDto
         */
        return $transaction->state->getData(SagaCreate::class);
    }
}
