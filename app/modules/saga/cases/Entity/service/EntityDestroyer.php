<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\service;

use kuaukutsu\poc\demo\shared\entity\pk\PrimaryUuidUpdate;
use kuaukutsu\poc\demo\shared\exception\ModelDeleteException;
use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\modules\saga\service\EntityService;

final class EntityDestroyer
{
    public function __construct(private readonly EntityService $service)
    {
    }

    /**
     * @param non-empty-string $uuid
     * @throws NotFoundException
     *  @throws ModelDeleteException
     */
    public function remove(string $uuid): void
    {
        $this->service->remove(
            new PrimaryUuidUpdate($uuid)
        );
    }
}
