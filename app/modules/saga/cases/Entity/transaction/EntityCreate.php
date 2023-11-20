<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\TransactionStepBase;
use kuaukutsu\poc\demo\shared\entity\UuidFactory;
use kuaukutsu\poc\demo\modules\saga\service\EntityService;
use kuaukutsu\poc\demo\modules\saga\models\EntityModel;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final class EntityCreate extends TransactionStepBase
{
    public function __construct(
        private readonly array $data,
        private readonly EntityService $service,
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    public function commit(): bool
    {
        $this->save(
            $this->service->create(
                $this->uuidFactory->createUuid7(),
                EntityModel::hydrate($this->data)
            )
        );

        return true;
    }

    public function rollback(): bool
    {
        $this->service->update(
            $this->current()->uuid,
            EntityModel::hydrate(
                [
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
        return $this->get(self::class);
    }
}
