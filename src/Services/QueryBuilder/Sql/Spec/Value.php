<?php

namespace Pkof\Services\QueryBuilder\Sql\Spec;

use Pkof\Services\QueryBuilder\ConnectionManager;
use Pkof\Services\QueryBuilder\Sql\SqlInterface;

class Value implements SqlInterface
{
    /**
     * @var
     */
    protected $values;

    /**
     * @param array $values
     *
     * @return $this
     */
    public function values(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        $values = (array)$this->values;
        $values = ConnectionManager::quote($values);

        $sets = [];
        foreach ($values as $column => $value) {
            $sets[] = $column . ' = ' . $value;
        }

        $result = implode(', ', $sets);

        return $result;
    }
}