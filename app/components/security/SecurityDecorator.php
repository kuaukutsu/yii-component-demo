<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\security;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Security;

/**
 * Оборачиваем внутренний Security, чтобы снизить зависимость от компоненты.
 */
final class SecurityDecorator extends Component implements SecurityInterface
{
    private readonly Security $security;

    public function __construct()
    {
        $this->security = Yii::$app->security;

        parent::__construct();
    }

    public function generateRandomKey(int $length = 32): string
    {
        return $this->security->generateRandomKey($length);
    }

    public function generateRandomString(int $length = 32): string
    {
        return $this->security->generateRandomString($length);
    }

    public function compareString(string $expected, string $actual): bool
    {
        return $this->security->compareString($expected, $actual);
    }

    public function generatePasswordHash(string $password, ?int $cost = null): string
    {
        return $this->security->generatePasswordHash($password, $cost);
    }

    public function validatePassword(string $password, string $hash): bool
    {
        return $this->security->validatePassword($password, $hash);
    }

    /**
     * @throws InvalidConfigException
     */
    public function validateData(string $data, string $key, bool $rawHash = false): bool | string
    {
        return $this->security->validateData($data, $key, $rawHash);
    }

    public function hashData(string $data, string $key, bool $rawHash = false): string
    {
        return $this->security->hashData($data, $key, $rawHash);
    }

    public function maskToken(string $token): string
    {
        return $this->security->maskToken($token);
    }

    public function unmaskToken(string $maskedToken): string
    {
        return $this->security->unmaskToken($maskedToken);
    }
}
