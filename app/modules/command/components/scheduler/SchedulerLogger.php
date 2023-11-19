<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command\components\scheduler;

use Yii;
use kuaukutsu\poc\cron\event\ProcessEvent;
use kuaukutsu\poc\cron\event\ProcessTimeoutEvent;
use kuaukutsu\poc\cron\EventInterface;
use kuaukutsu\poc\cron\EventSubscriberInterface;
use kuaukutsu\poc\cron\SchedulerEvent;

final class SchedulerLogger implements EventSubscriberInterface
{
    public function subscriptions(): array
    {
        /**
         * @var array<class-string<SchedulerEvent>, callable(SchedulerEvent $name, EventInterface $event):void>
         */
        return [
            SchedulerEvent::ProcessTimeout->value => $this->onProcessTimeout(...),
            SchedulerEvent::ProcessError->value => $this->onProcessError(...),
        ];
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function onProcessError(SchedulerEvent $name, ProcessEvent $event): void
    {
        Yii::error(
            [
                'message' => $event->getMessage(),
                'command' => $event->getCommand(),
                'output' => $event->getOutput(),
            ],
            'scheduler/error',
        );
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function onProcessTimeout(SchedulerEvent $name, ProcessTimeoutEvent $event): void
    {
        Yii::error(
            [
                'message' => $event->getMessage(),
                'command' => $event->getCommand(),
                'output' => $event->getOutput(),
            ],
            'scheduler/timeout',
        );
    }
}
