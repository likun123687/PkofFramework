<?php
namespace Pkof\Services\Cache;

use Pkof\Exceptions\Error\InvalidArgumentWithContextException;

trait PrefixTrait
{
    private $prefix;

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    private function getPrefixKey($key)
    {
        if (is_string($key)) {
            return $this->prefix . $key;
        } elseif (is_array($key)) {
            array_walk($key, function (&$itemValue, $itemKey, $prefix) {
                $itemValue = $this->prefix . $itemValue;
            }, $this->prefix);

            return $key;
        } else {
            throw new InvalidArgumentWithContextException('Error key type.', $key);
        }
    }
}