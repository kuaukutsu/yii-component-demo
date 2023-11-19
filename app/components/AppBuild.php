<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components;

use Yiisoft\Arrays\ArrayHelper;
use yii\base\BootstrapInterface;

/**
 * Builder configuration
 * @psalm-suppress UnresolvableInclude
 */
final class AppBuild
{
    public const LAYER_API = 'api';
    public const LAYER_CONSOLE = 'console';
    public const LAYER_TESTS = 'tests';

    private array $config = [];

    private readonly string $dirConfig;

    /**
     * @param string $layer enum: web, console, tests, long-running
     */
    public function __construct(
        array $baseConfig,
        string $dirConfig,
        private readonly string $layer = self::LAYER_API
    ) {
        $this->dirConfig = rtrim($dirConfig, '/');

        if (file_exists($this->dirConfig . '/main.php')) {
            $this->config = ArrayHelper::merge((array)require $this->dirConfig . '/main.php', $baseConfig);
        }
    }

    public function buildConfig(): array
    {
        $this->mergeComponentsWithLocal($this->config);
        $this->mergeContainerWithLocal($this->config);
        $this->mergeModules($this->config);
        $this->mergeComponentsStorage($this->config);
        $this->mergeComponentsRedis($this->config);
        $this->mergeParams($this->config);

        return $this->config;
    }

    private function isRunTests(): bool
    {
        return $this->layer === self::LAYER_TESTS;
    }

    /**
     * Устанавливаем локальные конфигурационные файлы components.local.php
     *
     * @param array $config rewrite
     */
    private function mergeComponentsWithLocal(array &$config): void
    {
        if (array_key_exists('components', $config) === false) {
            $config['components'] = [];
        }

        if (file_exists($this->dirConfig . '/components.local.php')) {
            $config['components'] = array_merge(
                (array)$config['components'],
                (array)require $this->dirConfig . '/components.local.php'
            );
        }
    }

    /**
     * Устанавливаем локальные конфигурационные файлы container.local.php
     *
     * @param array $config rewrite
     */
    private function mergeContainerWithLocal(array &$config): void
    {
        if (array_key_exists('container', $config) === false || is_array($config['container']) === false) {
            $config['container'] = [];
        }

        if (array_key_exists('singletons', $config['container']) === false) {
            $config['container']['singletons'] = [];
        }

        if (array_key_exists('definitions', $config['container']) === false) {
            $config['container']['definitions'] = [];
        }

        /**
         * @var array{
         *     "container": array{"singletons": array<string, mixed>, "definitions": array<string, mixed>}
         *     } $config
         */

        if (file_exists($this->dirConfig . '/container.local.php')) {
            $container = (array)require $this->dirConfig . '/container.local.php';

            if (isset($container['singletons']) && is_array($container['singletons'])) {
                $config['container']['singletons'] = array_merge(
                    (array)$config['container']['singletons'],
                    $container['singletons'],
                );
            }

            if (isset($container['definitions']) && is_array($container['definitions'])) {
                $config['container']['definitions'] = array_merge(
                    (array)$config['container']['definitions'],
                    $container['definitions'],
                );
            }
        }
    }

    /**
     * Устанавливаем локальные конфигурационные файлы modules.local
     *
     * @param array $config rewrite
     */
    private function mergeModules(array &$config): void
    {
        if (array_key_exists('bootstrap', $config) === false) {
            $config['bootstrap'] = [];
        }

        if (array_key_exists('modules', $config) === false) {
            $config['modules'] = [];
        }

        if (file_exists($this->dirConfig . '/modules.local.php')) {
            $modules = (array)require $this->dirConfig . '/modules.local.php';
            array_walk($modules, static function (array $value, string $key) use (&$config) {
                if (isset($value['class']) && is_subclass_of((string)$value['class'], BootstrapInterface::class)) {
                    $config['bootstrap'][] = $key;
                }

                $config['modules'][$key] = $value;
            });
        }
    }

    /**
     * @param array $config rewrite
     */
    private function mergeComponentsStorage(array &$config): void
    {
        $storage = (array)require $this->dirConfig
            . ($this->isRunTests() ? '/storage.test.php' : '/storage.php');

        if (file_exists($this->dirConfig . '/storage.local.php')) {
            $storage = array_merge(
                $storage,
                (array)require $this->dirConfig . '/storage.local.php'
            );
        }

        /**
         * Переписываем только если не определено глобально в конфиге
         */
        array_walk($storage, static function (array $value, string $key) use (&$config) {
            if (isset($config['components'][$key]) === false) {
                $config['components'][$key] = $value;
            }
        });
    }

    /**
     * @param array $config rewrite
     */
    private function mergeComponentsRedis(array &$config): void
    {
        $storage = (array)require $this->dirConfig
            . ($this->isRunTests() ? '/redis.tests.php' : '/redis.php');

        /**
         * Переписываем только если не определено глобально в конфиге
         */
        array_walk($storage, static function (array $value, string $key) use (&$config) {
            if (isset($config['components'][$key]) === false) {
                $config['components'][$key] = $value;
            }
        });
    }

    /**
     * @param array $config rewrite
     */
    private function mergeParams(array &$config): void
    {
        if (array_key_exists('params', $config) === false) {
            $config['params'] = [];
        }

        if (file_exists($this->dirConfig . '/params.local.php')) {
            $config['params'] = array_merge(
                (array)$config['params'],
                (array)require $this->dirConfig . '/params.local.php'
            );
        }
    }
}
