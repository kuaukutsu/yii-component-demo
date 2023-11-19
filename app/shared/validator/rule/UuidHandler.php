<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\validator\rule;

use Ramsey\Uuid\UuidFactory;
use Yiisoft\Validator\Exception\UnexpectedRuleException;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\RuleHandlerInterface;
use Yiisoft\Validator\ValidationContext;

final class UuidHandler implements RuleHandlerInterface
{
    private readonly UuidFactory $uuidFactory;

    public function __construct()
    {
        $this->uuidFactory = new UuidFactory();
    }

    public function validate(mixed $value, object $rule, ValidationContext $context): Result
    {
        if (!$rule instanceof Uuid) {
            throw new UnexpectedRuleException(Uuid::class, $rule);
        }

        if (is_string($value) === false || $this->uuidFactory->getValidator()->validate($value) === false) {
            return (new Result())->addError($rule->message);
        }

        return new Result();
    }
}
