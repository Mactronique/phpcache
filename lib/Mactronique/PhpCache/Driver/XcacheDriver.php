<?php

namespace Mactronique\PhpCache\Driver;

use Mactronique\PhpCache\Exception\DriverRequirementFailException;

class XcacheDriver implements Driver
{
    const NAME = 'xCache';

    public function checkDriver()
    {
        if (!extension_loaded('xcache') || !function_exists("xcache_set")) {
            throw new DriverRequirementFailException("xCache extension not loaded", self::NAME);
        }
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
        if (!$this->exists($key)) {
            return null;
        }
        $val = xcache_get($key);
        $obj = unserialize($val);

        return $obj;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        $serialized = serialize($value);

        if (null === $ttl) {
            return xcache_set($key, $serialized);
        }
        return xcache_set($key, $serialized, $ttl);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return xcache_isset($key);
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        return xcache_unset($key);
    }

    /**
     * Remove all keys and value from cache
     */
    public function clean()
    {
        $cnt = xcache_count(XC_TYPE_VAR);
        for ($i=0; $i < $cnt; $i++) {
            xcache_clear_cache(XC_TYPE_VAR, $i);
        }
        return true;
    }
}
