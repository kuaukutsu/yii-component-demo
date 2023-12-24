<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\service;

use kuaukutsu\poc\saga\exception\ProcessingException;
use kuaukutsu\poc\saga\TransactionRunner;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction\CreateTransaction;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final readonly class EntityDomainCreator
{
    public function __construct(private TransactionRunner $transaction)
    {
    }

    /**
     * @param array<string, string> $entityData
     * @param non-empty-string[] $tags
     * @throws ProcessingException
     */
    public function create(DomainIdentity $identity, array $entityData, array $tags): EntityDto
    {
        $transaction = $this->transaction->run(
            new CreateTransaction($identity, $entityData, $tags)
        );

        /**
         * @var EntityDto
         */
        return $transaction->state->get(EntityDto::class);
    }
}
