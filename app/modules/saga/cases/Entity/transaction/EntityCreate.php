<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\TransactionStepBase;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\service\EntityCreator;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\service\EntityDestroyer;
use kuaukutsu\poc\demo\modules\saga\models\EntityModel;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final class EntityCreate extends TransactionStepBase
{
    /**
     * @param array<string, scalar> $data
     */
    public function __construct(
        public readonly DomainIdentity $identity,
        public readonly array $data,
        private readonly EntityCreator $service,
        private readonly EntityDestroyer $destroyer,
    ) {
    }

    public function commit(): bool
    {
        $this->save(
            $this->service->create(
                $this->identity,
                EntityModel::hydrate($this->data)
            )
        );

        return true;
    }

    public function rollback(): bool
    {
        $this->destroyer->remove(
            $this->getSavedModel()->uuid
        );

        return true;
    }

    private function getSavedModel(): EntityDto
    {
        /**
         * @var EntityDto
         */
        return $this->current();
    }
}
