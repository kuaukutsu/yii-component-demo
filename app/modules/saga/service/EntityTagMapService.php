<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\service;

use kuaukutsu\poc\demo\shared\utils\ModelOperationSafely;
use kuaukutsu\poc\demo\shared\entity\pk\PrimaryKeyInterface;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\modules\saga\models\TagDto;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;
use kuaukutsu\poc\demo\modules\saga\models\EntityTagMap;

final class EntityTagMapService
{
    use ModelOperationSafely;

    /**
     * @throws ModelSaveException
     */
    public function create(EntityDto $entity, TagDto $tag): bool
    {
        return $this->insertSafely(
            new EntityTagMap(
                [
                    'entity_uuid' => $entity->uuid,
                    'tag_uuid' => $tag->uuid,
                ]
            )
        );
    }

    /**
     * Use only in rollback.
     */
    public function remove(PrimaryKeyInterface $pk): void
    {
        EntityTagMap::deleteAll($pk->getValue());
    }
}
