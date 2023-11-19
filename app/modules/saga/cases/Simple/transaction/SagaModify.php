<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Simple\transaction;

use kuaukutsu\poc\saga\TransactionStepBase;
use kuaukutsu\poc\demo\modules\saga\service\SagaService;
use kuaukutsu\poc\demo\modules\saga\models\SagaModel;
use kuaukutsu\poc\demo\modules\saga\models\SagaDto;

final class SagaModify extends TransactionStepBase
{
    public function __construct(
        private readonly SagaService $service,
    ) {
    }

    public function commit(): bool
    {
        $this->service->update(
            $this->current()->uuid,
            SagaModel::hydrate(
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
            SagaModel::hydrate(
                [
                    'comment' => $model->comment,
                    'flag' => false,
                ]
            )
        );

        return true;
    }

    private function current(): SagaDto
    {
        /**
         * @var SagaDto
         */
        return $this->get(SagaCreate::class);
    }
}
