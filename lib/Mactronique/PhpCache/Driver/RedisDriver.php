<?php

namespace Mactronique\PhpCache\Driver;

use Mactronique\PhpCache\Exception\DriverRequirementFailException;
use Mactronique\PhpCache\Exception\ServerException;

class RedisDriver implements Driver
{
    const NAME = 'Redis';

    private $config;

    private $client;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function checkDriver()
    {
        if (!class_exists('Redis')) {
            throw new DriverRequirementFailException("Redis extension not installed", self::NAME);
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
        $this->connectServer();
        $value = $this->client->get($key);

        if ($value == false) {
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
        $this->connectServer();
        return $this->client->set($keyword, $value, (null === $ttl)? 0:$ttl);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        $this->connectServer();
        return (null !== $this->client->exists($key));
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        $this->connectServer();
        return $this->client->delete($key);
    }

    /**
     * Remove all keys and value from cache
     */
    public function clean()
    {
        $this->connectServer();
        $this->client->flushDB();
        return true;
    }

    private function connectServer()
    {
        if (null === $this->client) {
            $host = $this->config['host'];
            $port = array_key_exists('port', $this->config)? (int)$this->config['port']:6379;
            $password = (array_key_exists('password', $this->config))? $this->config['password']:'';
            $database = (array_key_exists('database', $this->config)? (int)$this->config['database']:null);
            $timeout = (array_key_exists('timeout', $this->config))? (int)$this->config['timeout']:1;

            $this->client = new \Redis();
            if (!$this->client->connect($host, $port, $timeout)) {
                throw new ServerException("Error Unable to connect to server");
            }

            if (null !== $database) {
                $this->client->select($database);
            }
        }
    }
}
