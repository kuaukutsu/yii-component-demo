<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Entity\handler;

use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\components\bridge\BridgeResponse;
use kuaukutsu\poc\demo\modules\task\cases\Entity\service\EntityDomainCreator;
use kuaukutsu\poc\demo\modules\task\cases\Manage\dto\TaskDomainDto;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotImplementedException;
use kuaukutsu\poc\demo\shared\request\Task\EntityCreateRequest;

final readonly class CreateResponse implements BridgeResponse
{
    public function __construct(private EntityDomainCreator $service)
    {
    }

    /**
     * @throws ModelSaveException
     */
    public function handler(BridgeRequest $request): TaskDomainDto
    {
        if ($request instanceof EntityCreateRequest) {
            return $this->service->create(
                $request->identity,
                $request->title
            );
        }

        throw new NotImplementedException("EntityCreateRequest not implemented.");
    }
}
