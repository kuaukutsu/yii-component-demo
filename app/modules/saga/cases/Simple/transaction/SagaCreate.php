<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Simple\transaction;

use kuaukutsu\poc\saga\TransactionStepBase;
use kuaukutsu\poc\demo\shared\entity\UuidFactory;
use kuaukutsu\poc\demo\modules\saga\service\SagaService;
use kuaukutsu\poc\demo\modules\saga\models\SagaModel;
use kuaukutsu\poc\demo\modules\saga\models\SagaDto;

final class SagaCreate extends TransactionStepBase
{
    public function __construct(
        private readonly string $comment,
        private readonly SagaService $service,
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    public function commit(): bool
    {
        $this->save(
            $this->service->create(
                $this->uuidFactory->createUuid7(),
                SagaModel::hydrate(
                    [
                        'comment' => $this->comment,
                        'flag' => true,
                    ]
                )
            )
        );

        return true;
    }

    public function rollback(): bool
    {
        $this->service->update(
            $this->current()->uuid,
            SagaModel::hydrate(
                [
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
        return $this->get(self::class);
    }
}
