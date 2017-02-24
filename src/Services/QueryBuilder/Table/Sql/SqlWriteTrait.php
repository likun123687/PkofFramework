<?php

namespace Pkof\Services\QueryBuilder\Table\Sql;

trait SqlWriteTrait
{
    public function run()
    {
        return $this->getConnection()->exec($this->toString());
    }
}