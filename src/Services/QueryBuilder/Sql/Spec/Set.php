<?php

namespace Pkof\Services\QueryBuilder\Sql\Spec;

class Set extends Value
{
    public function toString()
    {
        return 'SET ' . parent::toString();
    }
}