<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command\cases\Crontab;

use yii\base\Module;
use yii\console\ExitCode;
use kuaukutsu\poc\demo\modules\command\components\controller\ConsoleCommand;

final class DatetimeCommand extends ConsoleCommand
{
    public function __construct(
        string $id,
        Module $module,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actionNow(): int
    {
        $this->stdout('now: ' . gmdate('c') . PHP_EOL);

        return ExitCode::OK;
    }
}
