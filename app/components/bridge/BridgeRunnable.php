<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\bridge;

use yii\base\Arrayable;
use yii\di\Container;
use kuaukutsu\ds\dto\DtoInterface;
use kuaukutsu\ds\collection\Collection;

final class BridgeRunnable
{
    public function __construct(private readonly Container $container)
    {
    }

    public function run(BridgeRequest $request): DtoInterface|Collection|Arrayable
    {
        return $this
            ->factoryResponseHandler($request->getHandler())
            ->handler($request);
    }

    private function factoryResponseHandler(string $class): BridgeResponse
    {
        /**
         * @var BridgeResponse
         * @noinspection PhpUnhandledExceptionInspection
         */
        return $this->container->get($class);
    }
}
