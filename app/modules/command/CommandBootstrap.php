<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command;

use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\console\Application as ApplicationConsole;
use kuaukutsu\poc\demo\modules\command\cases\Crontab\DatetimeCommand;

final class CommandBootstrap implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app): void
    {
        if ($app instanceof ApplicationConsole) {
            $this->registerConsole($app);
        }
    }

    private function registerConsole(ApplicationConsole $app): void
    {
        $app->controllerMap = array_merge(
            $app->controllerMap,
            [
                'datetime' => [
                    'class' => DatetimeCommand::class,
                ],
            ]
        );
    }
}
