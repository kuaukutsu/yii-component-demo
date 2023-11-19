<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\entity\pk;

use Yiisoft\Strings\Inflector;

/**
 * Составной ключ.
 * Особенности не может принимать пустое значение.
 */
final class PrimaryCompositeValue implements PrimaryKeyInterface
{
    /**
     * @var array<string, scalar>
     */
    private array $key = [];

    /**
     * @param array<string, scalar> $key
     */
    public function __construct(array $key, private readonly bool $isNewRecord = false)
    {
        foreach ($key as $name => $value) {
            $this->key[$this->castSnakeCase($name)] = $value;
        }
    }

    public function isNewRecord(): bool
    {
        return $this->isNewRecord;
    }

    /**
     * @return array<string, scalar>
     */
    public function getValue(): array
    {
        return $this->key;
    }

    private function castSnakeCase(string $name): string
    {
        return (new Inflector())->withoutIntl()->pascalCaseToId($name, '_');
    }
}
