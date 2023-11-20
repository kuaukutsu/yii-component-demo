<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\TransactionStepBase;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\saga\models\TagModel;
use kuaukutsu\poc\demo\modules\saga\cases\Tag\exception\TagExistsException;
use kuaukutsu\poc\demo\modules\saga\cases\Tag\service\TagCreator;

final class TagCreate extends TransactionStepBase
{
    /**
     * @param non-empty-string[] $tags
     */
    public function __construct(
        public readonly DomainIdentity $identity,
        public readonly array $tags,
        private readonly TagCreator $service,
    ) {
    }

    public function commit(): bool
    {
        foreach ($this->tags as $tag) {
            try {
                $this->service->create(
                    $this->identity,
                    TagModel::hydrate(
                        [
                            'name' => $tag,
                        ]
                    )
                );
            } catch (TagExistsException) {
            }
        }

        return true;
    }

    public function rollback(): bool
    {
        return true;
    }
}
