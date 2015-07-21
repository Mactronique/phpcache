<?php

namespace Mactronique\PhpCache\Driver\Service;

use Mactronique\PhpCache\Driver\Driver;
use Mactronique\PhpCache\Driver\Exception\UnknowDriverException;
use Mactronique\PhpCache\Driver\Exception\NoDriverException;

class PhpCache
{
    /**
     * Driver to use
     */
    private $drivers;

    private $default;

    public function __construct()
    {
        $this->drivers = [];
        $this->default = null;
    }

    public function registerDriver(Driver $driver)
    {
        $driver->checkDriver();

        $this->drivers[$driver->getName()] = $driver;
    }

    public function hasDriver($key)
    {
        return array_key_exists($key, $this->drivers);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key, $driverName = null)
    {
        return $this->getDriver($driverName)->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null, $driverName = null)
    {
        return $this->getDriver($driverName)->set($key, $value, $ttl = null);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key, $driverName = null)
    {
        return $this->getDriver($driverName)->exists($key);
    }

    /**
     * @param string $key
     */
    public function remove($key, $driverName = null)
    {
        return $this->getDriver($driverName)->remove($key);
    }

    /**
     * Remove all keys and value from cache
     */
    public function clean($driverName = null)
    {
        return $this->getDriver($driverName)->clean($key);
    }

    /**
     * return driver
     */
    private function getDriver($driverName = null)
    {
        if (null === $driverName) {
            $driver = $this->determineDefaultDriver();
        }

        if (! $this->hasDriver($driver)) {
            throw new UnknowDriverException("Error : not loaded driver '".$driver."'");
            
        }

        return $this->drivers[$driver];
    }

    /**
     * get the first driver available
     */
    private function determineDefaultDriver()
    {
        if (1 <= count($this->drivers)) {
            $keys = array_keys($this->drivers);
            return $keys[0];
        }

        throw new NoDriverException("No driver loaded", 255);
        
    }
}
