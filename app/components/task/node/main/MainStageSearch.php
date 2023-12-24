<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\node\main;

use yii\db\ActiveQuery;
use kuaukutsu\poc\demo\components\task\service\BaseStageSearch;

final class MainStageSearch extends BaseStageSearch
{
    protected function find(): ActiveQuery
    {
        return TaskMainStage::find();
    }
}
