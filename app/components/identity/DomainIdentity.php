<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\identity;

use yii\base\Arrayable;
use yii\web\IdentityInterface;

interface DomainIdentity extends IdentityInterface, Arrayable
{
    /**
     * @return non-empty-string UUID
     */
    public function getId(): string;

    /**
     * @return non-empty-string UUID
     */
    public function getNamespace(): string;

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * The returned key is used to validate session and auto-login (if [[User::enableAutoLogin]] is enabled).
     *
     * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
     * other scenarios, that require forceful access revocation for old sessions.
     *
     * @return non-empty-string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey(): string;

    /**
     * Validates the given auth key.
     *
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey): bool;

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return DomainIdentity|null the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id): DomainIdentity | null;

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]]
     * will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return DomainIdentity|null the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null): DomainIdentity | null;
}
