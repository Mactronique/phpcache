<?php

namespace Mactronique\PhpCache\Driver;

use Mactronique\PhpCache\Exception\DriverRequirementFailException;
use Mactronique\PhpCache\Exception\ServerException;

class MemcachedDriver implements Driver
{
    const NAME = 'Memcached';

    private $config;

    private $client;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function checkDriver()
    {
        if (!class_exists('Memcached')) {
            throw new DriverRequirementFailException("Memcached extension not installed", self::NAME);
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

        return $this->client->get($key);
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
        return $this->client->set($keyword, $value, (null === $ttl)? 0:(time()+(int)$ttl));
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        $this->connectServer();
        return (null !== $this->client->get($key));
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
        $this->client->flush();
        return true;
    }

    private function connectServer()
    {
        if (null === $this->client) {
            $this->client = new Memcached();

            $host = array_key_exists('host', $this->config)? $this->config['host']:'127.0.0.1';
            $port = array_key_exists('port', $this->config)? (int)$this->config['port']:11211;
            $sharing = (array_key_exists('sharing', $this->config))? (int)$this->config['sharing']:100;
            if ($sharing > 0) {
                if (!$this->client->addServer($host, $port, $sharing)) {
                    throw new ServerException("Error Unable to connect to server");
                }
            } else {
                if (!$this->client->addServer($host, $port)) {
                    throw new ServerException("Error Unable to connect to server");
                }
            }
        }
    }
}
