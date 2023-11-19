<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\exception;

use RuntimeException;
use Throwable;

final class NotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Not found.', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
