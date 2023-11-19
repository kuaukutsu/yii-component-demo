<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\identity;

enum DomainRoleEnum: string
{
    case GUEST = 'guest';

    case USER = 'user';

    case OWNER = 'owner';

    case ROOT = 'root';
}
