<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\service;

use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\modules\saga\models\SagaDto;
use kuaukutsu\poc\demo\modules\saga\models\Saga;

final class SagaSearch
{
    /**
     * @param non-empty-string $uuid
     * @throws NotFoundException
     */
    public function getOne(string $uuid): SagaDto
    {
        $model = Saga::findOne($uuid)
            ?? throw new NotFoundException("[$uuid] Saga not found.");

        return $model->toDto();
    }
}
