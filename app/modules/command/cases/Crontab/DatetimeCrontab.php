<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command\cases\Crontab;

use Symfony\Component\Process\Process;
use kuaukutsu\poc\demo\modules\command\components\scheduler\BaseProcess;

/**
 * @psalm-immutable
 */
final class DatetimeCrontab extends BaseProcess
{
    public function getName(): string
    {
        return 'datetime/now';
    }

    public function getProcess(): Process
    {
        return $this->generateProcess(
            [
                'datetime/now',
            ],
            5.
        );
    }
}
