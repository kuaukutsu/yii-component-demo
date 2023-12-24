<?php

declare(strict_types=1);

namespace kuaukutsu\poc\demo\components\task\node\main;

use yii\db\ActiveQuery;
use kuaukutsu\poc\demo\components\task\service\BaseTaskSearch;

final class MainTaskSearch extends BaseTaskSearch
{
    protected function find(): ActiveQuery
    {
        return TaskMain::find();
    }
}
