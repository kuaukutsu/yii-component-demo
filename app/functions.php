<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo;

if (function_exists('kuaukutsu\poc\demo\argument') === false) {
    function argument(string $name, string | int | null $default = null): string | int | null
    {
        global $argv;

        foreach ($argv as $item) {
            if (str_starts_with($item, '--')) {
                [$key, $value] = explode('=', ltrim($item, '-'));
                if ($key === $name) {
                    return $value;
                }
            }
        }

        return $default;
    }
}

if (function_exists('kuaukutsu\poc\demo\boolean') === false) {
    function boolean(bool|int|string $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return match ((string)$value) {
            '1', 'true' => true,
            default => false,
        };
    }
}

if (function_exists('kuaukutsu\poc\demo\getenvironment') === false) {
    function getenvironment(string $name, bool|int|string $default): bool|int|string
    {
        return getenv($name) ?: $default;
    }
}
