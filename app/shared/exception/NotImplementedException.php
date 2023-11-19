<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\exception;

use RuntimeException;

final class NotImplementedException extends RuntimeException
{
    public function __construct(string $message = 'Not Implemented.')
    {
        parent::__construct($message);
    }
}
