<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command\components\controller;

use yii\console\Controller;

abstract class ConsoleCommand extends Controller
{
    /**
     * @var bool verbose mode of a job execute. If enabled, execute result of each job
     * will be printed.
     */
    public bool $verbose = false;

    /**
     * @inheritdoc
     */
    public function options($actionID): array
    {
        $options = parent::options($actionID);
        $options[] = 'verbose';

        return $options;
    }

    /**
     * @inheritdoc
     */
    public function optionAliases(): array
    {
        return array_merge(
            parent::optionAliases(),
            [
                'v' => 'verbose',
            ]
        );
    }
}
