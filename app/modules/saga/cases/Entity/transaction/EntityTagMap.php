<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\TransactionStepBase;
use kuaukutsu\poc\demo\modules\saga\service\EntityService;
use kuaukutsu\poc\demo\modules\saga\models\EntityModel;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final class EntityTagMap extends TransactionStepBase
{
    public function __construct(
        private readonly EntityService $service,
    ) {
    }

    public function commit(): bool
    {
        $this->service->update(
            $this->current()->uuid,
            EntityModel::hydrate(
                [
                    'comment' => 'modify',
                    'flag' => true,
                ]
            )
        );

        return true;
    }

    public function rollback(): bool
    {
        $model = $this->current();

        $this->service->update(
            $model->uuid,
            EntityModel::hydrate(
                [
                    'comment' => $model->comment,
                    'flag' => false,
                ]
            )
        );

        return true;
    }

    private function current(): EntityDto
    {
        /**
         * @var EntityDto
         */
        return $this->get(EntityCreate::class);
    }
}
