<?php

namespace Pkof\Services\QueryBuilder\Sql\Spec;

class On extends Filter
{
    /**
     * @var string
     */
    protected $type = 'ON';

    /**
     * @return $this
     */
    public function on()
    {
        return call_user_func_array([$this, 'add'], func_get_args());
    }
}