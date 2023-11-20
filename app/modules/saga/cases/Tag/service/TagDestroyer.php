<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Tag\service;

use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidUpdate;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\modules\saga\cases\Tag\handler\UuidFactory;
use kuaukutsu\poc\demo\modules\saga\service\TagService;

final class TagDestroyer
{
    public function __construct(
        private readonly TagService $service,
        private readonly UuidFactory $uuidFactory,
    ) {
    }

    /**
     * @param non-empty-string $uuid
     * @throws NotFoundException
     * @throws ModelDeleteException
     */
    public function remove(string $uuid): void
    {
        $this->service->remove(
            new PrimaryUuidUpdate($uuid)
        );
    }

    /**
     * @param non-empty-string $name
     * @throws NotFoundException
     * @throws ModelDeleteException
     */
    public function removeByName(string $name): void
    {
        $this->service->remove(
            new PrimaryUuidUpdate(
                $this->uuidFactory->create($name)
            )
        );
    }
}
