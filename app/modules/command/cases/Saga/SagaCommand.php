<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command\cases\Saga;

use yii\base\Module;
use yii\console\ExitCode;
use kuaukutsu\poc\demo\modules\command\components\controller\ConsoleCommand;

final class SagaCommand extends ConsoleCommand
{
    public function __construct(
        string $id,
        Module $module,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionTest(): int
    {
        return ExitCode::OK;
    }
}
