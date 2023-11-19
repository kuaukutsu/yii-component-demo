<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\identity;

use Throwable;
use RuntimeException;

final class DomainIdentityException extends RuntimeException
{
    public function __construct(string $message, ?Throwable $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}
