<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\validator\rule;

use Attribute;
use Yiisoft\Validator\Rule\Trait\SkipOnEmptyTrait;
use Yiisoft\Validator\RuleInterface;
use Yiisoft\Validator\SkipOnEmptyInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final readonly class Uuid implements RuleInterface, SkipOnEmptyInterface
{
    use SkipOnEmptyTrait;

    public function __construct(
        public string $message = 'The current {value} is not UUID.',
        private bool $skipOnEmpty = true,
    ) {
    }

    public function getName(): string
    {
        return 'uuid';
    }

    public function getHandler(): string
    {
        return UuidHandler::class;
    }
}
