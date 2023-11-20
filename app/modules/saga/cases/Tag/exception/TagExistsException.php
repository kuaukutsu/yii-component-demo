<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Tag\exception;

use LogicException;

final class TagExistsException extends LogicException
{
    public function __construct(string $name)
    {
        parent::__construct("[$name] Tag is exists.");
    }
}
