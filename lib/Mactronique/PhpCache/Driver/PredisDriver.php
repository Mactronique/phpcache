<?php

namespace Mactronique\PhpCache\Driver;

use Mactronique\PhpCache\Exception\DriverRequirementFailException;

class PredisDriver implements Driver
{
    const NAME = 'Predis';

    private $config;

    private $client;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function checkDriver()
    {
        if (!extension_loaded('Predis\Client')) {
            throw new DriverRequirementFailException("Predis library not installed", self::NAME);
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
        return $this->client->setex($keyword, $value, (null === $ttl)? 0:$ttl);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        $this->connectServer();
        return (null === $this->client->exists($key));
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
            $clientConf = ['host' => $this->config['host']];

            if (array_key_exists('port', $this->config)) {
                $clientConf['port'] = $this->config['port'];
            }
            if (array_key_exists('password', $this->config)) {
                $clientConf['password'] = $this->config['password'];
            }
            if (array_key_exists('database', $this->config)) {
                $clientConf['database'] = $this->config['database'];
            }
            if (array_key_exists('timeout', $this->config)) {
                $clientConf['timeout'] = $this->config['timeout'];
            }
            if (array_key_exists('read_write_timeout', $this->config)) {
                $clientConf['read_write_timeout'] = $this->config['read_write_timeout'];
            }

            $this->client = new Predis\Client($clientConf);
            if (!$this->client) {
                throw new \Exception("Error Unable to connect to server");
            }
        }
    }
}
