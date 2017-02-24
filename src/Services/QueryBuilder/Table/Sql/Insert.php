<?php

namespace Pkof\Services\QueryBuilder\Table\Sql;

class Insert extends \Pkof\Services\QueryBuilder\Sql\Insert
{
    use SqlTrait;
    use SqlWriteTrait
    {
        run as protected traitRun;
    }

    /**
     * @param bool $returnsId
     * @return string
     */
    public function run($returnsId = true)
    {
        $result = $this->traitRun();

        if ($returnsId) {
            return $this->getConnection()->getLastInsertId();
        }

        return $result;
    }
}