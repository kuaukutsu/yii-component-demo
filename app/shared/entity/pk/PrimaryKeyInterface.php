<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\entity\pk;

/**
 * Данный контракт для определения и передачи значения PK
 * По сути, описывает Value Object - PrimaryKeyValue
 */
interface PrimaryKeyInterface
{
    /**
     * @return bool Признак того, что данная запись новая, т.е. ещё не было фиксации в storage.
     */
    public function isNewRecord(): bool;

    /**
     * @return array В общем случае имеет вид array{uuid: null|string}, null так как у нас auto increment.
     */
    public function getValue(): array;
}
