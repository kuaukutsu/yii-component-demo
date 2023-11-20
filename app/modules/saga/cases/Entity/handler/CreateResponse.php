<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Entity\handler;

use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\components\bridge\BridgeResponse;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotImplementedException;
use kuaukutsu\poc\demo\shared\request\Saga\EntityCreateRequest;
use kuaukutsu\poc\demo\modules\saga\cases\Entity\service\EntityCreator;
use kuaukutsu\poc\demo\modules\saga\models\EntityDto;

final class CreateResponse implements BridgeResponse
{
    public function __construct(private readonly EntityCreator $service)
    {
    }

    /**
     * @throws ModelSaveException
     */
    public function handler(BridgeRequest $request): EntityDto
    {
        if ($request instanceof EntityCreateRequest) {
            return $this->service->create(
                $request->identity,
                $request->entityData,
                $request->tags,
            );
        }

        throw new NotImplementedException("EntityCreateRequest not implemented.");
    }
}
