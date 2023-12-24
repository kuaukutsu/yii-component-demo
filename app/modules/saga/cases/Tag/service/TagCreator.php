<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Tag\service;

use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\modules\saga\cases\Tag\exception\TagExistsException;
use kuaukutsu\poc\demo\modules\saga\service\TagService;
use kuaukutsu\poc\demo\modules\saga\models\TagDto;
use kuaukutsu\poc\demo\modules\saga\models\TagModel;
use kuaukutsu\poc\demo\modules\saga\cases\Tag\handler\UuidFactory;

final readonly class TagCreator
{
    public function __construct(
        private TagService $service,
        private UuidFactory $uuidFactory,
    ) {
    }

    /**
     * @throws TagExistsException
     * @throws ModelSaveException
     */
    public function create(DomainIdentity $identity, TagModel $model): TagDto
    {
        try {
            return $this->service->create(
                $this->uuidFactory->create($model->name),
                $model
            );
        } catch (ModelSaveException $exception) {
            if ($exception->isDuplicateKey()) {
                throw new TagExistsException($model->name);
            }

            throw $exception;
        }
    }
}
