<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\exception;

use RuntimeException;
use yii\base\Model;

final class ModelSaveException extends RuntimeException
{
    public function __construct(
        Model $model,
        string $message = '{model} save error: {errors}',
        int $code = null,
    ) {
        $listErrors = [];
        if ($model->hasErrors()) {
            /**
             * @var string $attribute
             * @var string[] $errors
             */
            foreach ($model->getErrors() as $attribute => $errors) {
                $listErrors[] = "[$attribute] " . implode('; ', $errors);
            }
        }

        $placeholder = [
            '{model}' => $model::class,
            '{errors}' => $listErrors === [] ? '(empty)' : implode('; ', $listErrors),
        ];

        parent::__construct(
            strtr($message, $placeholder),
            $code ?? ModelExceptionEnum::DID_NOT_SAVE_MODEL->value
        );
    }

    public function isDuplicateKey(): bool
    {
        return $this->code === ModelExceptionEnum::SQLSTATE_DUPLICATE_KEY->value;
    }

    /**
     * SQLSTATE[40P01]: Deadlock detected: 7 ERROR: ...
     */
    public function isDeadLock(): bool
    {
        return str_starts_with($this->message, 'SQLSTATE[40P01]: Deadlock detected');
    }
}
