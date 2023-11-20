<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\service;

use kuaukutsu\poc\saga\TransactionRunner;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction\EntityCreate;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction\CreateTransaction;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final class EntityCreator
{
    public function __construct(
        private readonly TransactionRunner $transaction,
    ) {
    }

    /**
     * @throws ModelDeleteException
     */
    public function create(DomainIdentity $identity, array $entityData, array $tags): EntityDto
    {
        $transaction = $this->transaction->run(
            new CreateTransaction($entityData, $tags)
        );

        /**
         * @var EntityDto
         */
        return $transaction->state->getData(EntityCreate::class);
    }
}
