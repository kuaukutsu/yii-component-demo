<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\identity;

use Throwable;

final class DomainIdentityFactory
{
    /**
     * @param class-string<DomainIdentity> $identityClass
     * @param class-string<DomainIdentity> $nobodyClass
     */
    public function __construct(
        private readonly string $identityClass,
        private readonly string $nobodyClass,
    ) {
    }

    /**
     * Только для Nobody.
     * @throws DomainIdentityException
     */
    public function createByUuid(string $uuid): DomainIdentity
    {
        $identity = $this->findIdentityByUuid($this->nobodyClass, $uuid);
        if ($identity === null) {
            throw new DomainIdentityException("[$uuid] authorization failed.");
        }

        return $identity;
    }

    /**
     * @throws DomainIdentityException
     */
    public function createByToken(string $token): DomainIdentity
    {
        $identity = $this->findIdentityByAccessToken($this->identityClass, $token)
            ?? $this->findIdentityByAccessToken($this->nobodyClass, $token);

        if ($identity === null) {
            throw new DomainIdentityException("[$token] authorization failed.");
        }

        return $identity;
    }

    /**
     * @param class-string<DomainIdentity> $identityClass
     */
    private function findIdentityByAccessToken(string $identityClass, string $token): ?DomainIdentity
    {
        try {
            $identity = $identityClass::findIdentityByAccessToken($token);
        } catch (Throwable $e) {
            throw new DomainIdentityException($e->getMessage(), $e);
        }

        return $identity;
    }

    /**
     * @param class-string<DomainIdentity> $identityClass
     */
    private function findIdentityByUuid(string $identityClass, string $uuid): ?DomainIdentity
    {
        try {
            $identity = $identityClass::findIdentity($uuid);
        } catch (Throwable $e) {
            throw new DomainIdentityException($e->getMessage(), $e);
        }

        return $identity;
    }
}
