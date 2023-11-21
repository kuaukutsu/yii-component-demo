<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\transaction;

use kuaukutsu\poc\saga\TransactionStepBase;
use kuaukutsu\poc\demo\components\identity\DomainIdentity;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\service\EntityCreator;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final class EntityTagMap extends TransactionStepBase
{
    /**
     * @param non-empty-string[] $tags
     */
    public function __construct(
        public readonly DomainIdentity $identity,
        public readonly array $tags,
        private readonly EntityCreator $service,
    ) {
    }

    public function commit(): bool
    {
        $entity = $this->getEntity();
        foreach ($this->tags as $tag) {
            $this->service->attachTag(
                $this->identity,
                $entity,
                $tag,
            );
        }

        return true;
    }

    public function rollback(): bool
    {
        return true;
    }

    private function getEntity(): EntityDto
    {
        /**
         * @var EntityDto
         */
        return $this->get(EntityDto::class);
    }
}
