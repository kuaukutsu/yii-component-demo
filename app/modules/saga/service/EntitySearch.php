<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\service;

use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;
use kuaukutsu\poc\demo\modules\saga\models\Entity;

final class EntitySearch
{
    /**
     * @param non-empty-string $uuid
     * @throws NotFoundException
     */
    public function getOne(string $uuid): EntityDto
    {
        $model = Entity::findOne($uuid)
            ?? throw new NotFoundException("[$uuid] Saga not found.");

        return $model->toDto();
    }
}
