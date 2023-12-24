<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\identity;

final readonly class GuestIdentity implements DomainIdentity
{
    /**
     * @param non-empty-string $token
     */
    public function __construct(private string $token = '00000000-0000-0000-0000-000000000000')
    {
    }

    public function getId(): string
    {
        return '00000000-0000-0000-0000-000000000000';
    }

    public function getNamespace(): string
    {
        return '00000000-0000-0000-0000-000000000000';
    }

    public function getAuthKey(): string
    {
        return $this->token;
    }

    public function validateAuthKey($authKey): bool
    {
        return $authKey === $this->getAuthKey();
    }

    public static function findIdentity($id): DomainIdentity|null
    {
        return new self();
    }

    public static function findIdentityByAccessToken($token, $type = null): DomainIdentity|null
    {
        return new self();
    }

    public function fields(): array
    {
        return [];
    }

    public function extraFields(): array
    {
        return [];
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        return [
            'id' => $this->getId(),
            'namespace' => $this->getNamespace(),
            'access_token' => $this->getAuthKey(),
        ];
    }
}
