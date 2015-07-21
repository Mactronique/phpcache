<?php

namespace Mactronique\PhpCache\Driver\Driver;

class WincacheDriver implements Driver
{
    const NAME = 'WinCache';

    public static function checkDriver()
    {
        if (!extension_loaded('wincache') || !function_exists("wincache_ucache_set")) {
            throw new DriverRequirementFailException("Wincache extension not loaded", self::NAME);
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
        $value = wincache_ucache_get($key, $sucess);

        if ($sucess == false) {
            return null;
        }
        return $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null)
    {
        if (!$this->exists($key)) {
            return wincache_ucache_add($keyword, $value, (null === $ttl)? 0:$ttl);
        }
        
        return wincache_ucache_set($keyword, $value, (null === $ttl)? 0:$ttl);
        
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return wincache_ucache_exists($key);
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        return wincache_ucache_delete($key);
    }

    /**
     * Remove all keys and value from cache
     */
    public function clean()
    {
        wincache_ucache_clear();
        return true;
    }
}
