<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\service;

use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\shared\entity\UuidFactory;
use kuaukutsu\poc\demo\modules\saga\service\EntityService;
use kuaukutsu\poc\demo\modules\saga\service\EntityTagMapService;
use kuaukutsu\poc\demo\modules\saga\service\TagSearch;
use kuaukutsu\poc\demo\modules\saga\models\EntityModel;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final class EntityCreator
{
    public function __construct(
        private readonly EntityService $service,
        private readonly TagSearch $tagSearch,
        private readonly EntityTagMapService $mapService,
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    /**
     * @throws ModelSaveException
     */
    public function create(DomainIdentity $identity, EntityModel $model): EntityDto
    {
        return $this->service->create(
            $this->uuidFactory->createUuid7(),
            $model
        );
    }

    /**
     * @param non-empty-string $tagName
     * @throws NotFoundException
     * @throws ModelSaveException
     */
    public function attachTag(DomainIdentity $identity, EntityDto $entity, string $tagName): void
    {
        $this->mapService->create(
            $entity,
            $this->tagSearch->getOneByName($tagName)
        );
    }
}
