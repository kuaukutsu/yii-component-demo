<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\validator;

use Ramsey\Uuid\UuidFactory;
use yii\validators\Validator;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class UuidModelValidator extends Validator
{
    public $skipOnEmpty = false;

    public string $messageError = 'The current {value} is not UUID.';

    public string $messageIsEmpty = 'The attribute {attribute} must be declared.';

    public function __construct(
        private readonly UuidFactory $uuidFactory,
        array $config = [],
    ) {
        parent::__construct($config);
    }

    public function validateAttribute($model, $attribute): void
    {
        /** @var string|null $uuid */
        $uuid = $model->$attribute;

        if ($uuid === null) {
            $this->addError($model, $attribute, $this->messageIsEmpty);
            return;
        }

        if ($this->uuidFactory->getValidator()->validate($uuid) === false) {
            $this->addError($model, $attribute, $this->messageError);
        }
    }
}
