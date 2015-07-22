<?php

namespace Mactronique\PhpCache\Driver;

interface Driver
{
    /**
     * Check if driver is usable
     * @throws DriverRequirementFailException
     */
    public function checkDriver();

    /**
     * @return string Driver name
     */
    public function getName();

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = null);

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key);

    /**
     * @param string $key
     */
    public function remove($key);

    /**
     * Remove all keys and value from cache
     */
    public function clean();
}
