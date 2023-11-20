<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Tag\handler;

final class UuidFactory
{
    /**
     * @param non-empty-string $name
     * @return non-empty-string
     */
    public function create(string $name): string
    {
        /**
         * @var non-empty-string
         */
        return preg_replace(
            '~^(\w{8})(\w{4})(\w{4})(\w{4})(\w{12})$~',
            '\1-\2-\3-\4-\5',
            hash('md5', $name)
        );
    }
}
