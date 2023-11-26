<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\container;

use DI\FactoryInterface;
use Psr\Container\ContainerInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

final class ContainerDecorator implements ContainerInterface, FactoryInterface
{
    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public function get(string $id)
    {
        return Yii::$container->get($id);
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     */
    public function has(string $id): bool
    {
        return Yii::$container->has($id);
    }

    /**
     * @psalm-suppress MixedInferredReturnType
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public function make(string $name, array $parameters = []): object
    {
        return Yii::$container->get(
            $name,
            [],
            [
                '__construct()' => $parameters,
            ],
        );
    }
}
