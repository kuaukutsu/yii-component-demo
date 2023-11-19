<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\shared\exception;

enum ModelExceptionEnum: int
{
    /**
     * По умолчанию задаётся для ошибок типа \app\shared\exception\FormValidationException
     */
    case DID_NOT_PASS_VALIDATION = 14001;

    /**
     * По умолчанию задаётся для ошибок типа \app\shared\exception\ModelSaveException
     */
    case DID_NOT_SAVE_MODEL = 14002;

    /**
     * По умолчанию задаётся для ошибок типа \app\shared\exception\ModelDeleteException
     */
    case DID_NOT_DELETE_MODEL = 14003;

    /**
     * SQLSTATE[23505]: Unique violation: 7 ERROR:  duplicate key value violates unique constraint
     */
    case SQLSTATE_DUPLICATE_KEY = 23505;
}
