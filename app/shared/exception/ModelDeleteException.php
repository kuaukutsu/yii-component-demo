<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\exception;

use RuntimeException;
use Throwable;

final class ModelDeleteException extends RuntimeException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, ModelExceptionEnum::DID_NOT_DELETE_MODEL->value, $previous);
    }
}
