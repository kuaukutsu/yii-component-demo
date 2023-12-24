<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\service;

use kuaukutsu\poc\demo\shared\exception\NotFoundException;
use kuaukutsu\poc\demo\modules\saga\cases\Tag\handler\UuidFactory;
use kuaukutsu\poc\demo\modules\saga\models\TagDto;
use kuaukutsu\poc\demo\modules\saga\models\Tag;

final readonly class TagSearch
{
    public function __construct(private UuidFactory $uuidFactory)
    {
    }

    /**
     * @param non-empty-string $uuid
     * @throws NotFoundException
     */
    public function getOne(string $uuid): TagDto
    {
        $model = Tag::findOne($uuid)
            ?? throw new NotFoundException("[$uuid] Tag not found.");

        return $model->toDto();
    }

    /**
     * @param non-empty-string $name
     * @throws NotFoundException
     */
    public function getOneByName(string $name): TagDto
    {
        return $this->getOne(
            $this->uuidFactory->create($name)
        );
    }
}
