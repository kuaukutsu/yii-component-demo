<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command\components\scheduler;

use Yii;
use kuaukutsu\poc\cron\event\ProcessEvent;
use kuaukutsu\poc\cron\EventInterface;
use kuaukutsu\poc\cron\EventSubscriberInterface;
use kuaukutsu\poc\cron\SchedulerEvent;

final class SchedulerTrace implements EventSubscriberInterface
{
    public function subscriptions(): array
    {
        /**
         * @var array<class-string<SchedulerEvent>, callable(SchedulerEvent $name, EventInterface $event):void>
         */
        return [
            SchedulerEvent::ProcessPush->value => $this->onProcessTrace(...),
            SchedulerEvent::ProcessStop->value => $this->onProcessTrace(...),
            SchedulerEvent::ProcessTimeout->value => $this->onProcessTrace(...),
            SchedulerEvent::ProcessSuccess->value => $this->onProcessTrace(...),
            SchedulerEvent::ProcessError->value => $this->onProcessTrace(...),
        ];
    }

    public function onProcessTrace(SchedulerEvent $name, ProcessEvent $event): void
    {
        Yii::info(
            [
                'message' => $event->getMessage(),
                'command' => $event->getCommand(),
                'output' => $event->getOutput(),
            ],
            'scheduler/trace',
        );
    }
}
