<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\command\components\scheduler;

use Symfony\Component\Process\Process;
use kuaukutsu\poc\cron\tools\ProcessUuid;
use kuaukutsu\poc\cron\ProcessInterface;

/**
 * @psalm-immutable
 */
abstract class BaseProcess implements ProcessInterface
{
    /**
     * @return non-empty-string
     */
    abstract public function getName(): string;

    public function getUuid(): ProcessUuid
    {
        return new ProcessUuid($this->getName());
    }

    /**
     * @param string[] $command
     * @psalm-pure
     */
    protected function generateProcess(array $command, float $timeout = 300.): Process
    {
        $cmd = [
            PHP_BINARY,
            'main/scheduler',
            ...$command,
        ];

        if (YII_DEBUG) {
            $cmd[] = '-v';
        }

        /**
         * @psalm-suppress ImpureMethodCall
         * @psalm-suppress ImpureFunctionCall
         */
        return (new Process($cmd, dirname(__DIR__, 4), getenv(), null, $timeout));
    }
}
