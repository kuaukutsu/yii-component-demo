<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\bridge;

use yii\base\Arrayable;
use kuaukutsu\ds\dto\DtoInterface;
use kuaukutsu\ds\collection\Collection;

interface BridgeResponse
{
    public function handler(BridgeRequest $request): DtoInterface|Collection|Arrayable;
}
