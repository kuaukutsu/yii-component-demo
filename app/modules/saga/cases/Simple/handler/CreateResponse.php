<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\saga\cases\Simple\handler;

use LogicException;
use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\components\bridge\BridgeResponse;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\request\Saga\SagaSimpleRequest;
use kuaukutsu\poc\demo\modules\saga\cases\Simple\service\SagaCreator;
use kuaukutsu\poc\demo\modules\saga\models\SagaDto;

final class CreateResponse implements BridgeResponse
{
    public function __construct(private readonly SagaCreator $service)
    {
    }

    /**
     * @throws ModelSaveException
     */
    public function handler(BridgeRequest $request): SagaDto
    {
        if ($request instanceof SagaSimpleRequest) {
            return $this->service->create(
                $request->identity,
                $request->comment,
            );
        }

        throw new LogicException("SagaRequest not implemented.");
    }
}
