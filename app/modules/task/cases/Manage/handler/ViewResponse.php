<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\modules\task\cases\Manage\handler;

use kuaukutsu\poc\demo\components\bridge\BridgeRequest;
use kuaukutsu\poc\demo\components\bridge\BridgeResponse;
use kuaukutsu\poc\demo\modules\task\cases\Manage\dto\TaskDomainDto;
use kuaukutsu\poc\demo\modules\task\cases\Manage\service\TaskDomainViewer;
use kuaukutsu\poc\demo\shared\exception\ModelSaveException;
use kuaukutsu\poc\demo\shared\exception\NotImplementedException;
use kuaukutsu\poc\demo\shared\request\Task\TaskViewRequest;

final class ViewResponse implements BridgeResponse
{
    public function __construct(private readonly TaskDomainViewer $service)
    {
    }

    /**
     * @throws ModelSaveException
     */
    public function handler(BridgeRequest $request): TaskDomainDto
    {
        if ($request instanceof TaskViewRequest) {
            return $this->service->view($request->uuid);
        }

        throw new NotImplementedException("TaskViewRequest not implemented.");
    }
}
