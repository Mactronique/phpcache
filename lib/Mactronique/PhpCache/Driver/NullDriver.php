<?php

namespace Mactronique\PhpCache\Driver;

use Mactronique\PhpCache\Exception\DriverRequirementFailException;

class NullDriver implements Driver
{
    const NAME = 'null';

    public function checkDriver()
    {
    }

    /**
     * @return string Driver name
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        return true;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return false;
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        return true;
    }

    /**
     * Remove all keys and value from cache
     */
    public function clean()
    {
        return true;
    }
}
