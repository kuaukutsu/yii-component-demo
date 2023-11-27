<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\handler;

use kuaukutsu\poc\demo\modules\task\cases\Entity\EntityNumber;

final class EntitySomeHandler
{
    public function handle(int $number): EntityNumber
    {
        return new EntityNumber($number);
    }
}
