<?php

namespace Mactronique\PhpCache\Tests\Units\Service;

use atoum;
use mock;

class PhpCache extends atoum
{
    public function testInit()
    {
        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->getName = 'test';

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->variable($service->registerDriver($driver))->isNull();

        $this->boolean($service->hasDriver('test'))->isTrue();
        $this->boolean($service->hasDriver('xCache'))->isFalse();
    }

    public function testInitNoDriver()
    {

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->boolean($service->hasDriver('xCache'))->isFalse();

        $this->exception(function () use ($service) {
            $service->get('key');
        })->isInstanceOf('Mactronique\PhpCache\Exception\NoDriverException');
           
    }

    public function testInitNoDriverRequested()
    {

        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->getName = 'test';

        $service = new \Mactronique\PhpCache\Service\PhpCache();
        $service->registerDriver($driver);

        $this->boolean($service->hasDriver('xCache'))->isFalse();

        $this->exception(function () use ($service) {
            $service->get('key', 'xcache');
        })->isInstanceOf('Mactronique\PhpCache\Exception\UnknowDriverException');
           
    }

    public function testGet()
    {
        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->getName = 'test';
        $driver->getMockController()->get = 'valeur';

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->variable($service->registerDriver($driver))->isNull();

        $this->string($service->get('key'))->isEqualTo('valeur');

        $this->mock($driver)->call('get')->once();
        //$this->mock($driver)->call('getDriver')->once();
           
    }

    public function testGetWithDriverName()
    {
        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->getName = 'test';
        $driver->getMockController()->get = 'valeur';

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->variable($service->registerDriver($driver))->isNull();

        $this->string($service->get('key', 'test'))->isEqualTo('valeur');

        $this->mock($driver)->call('get')->once();
        $this->mock($driver)->call('getName')->twice();
           
    }
    public function testSet()
    {
        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->set = true;
        $driver->getMockController()->getName = 'test';

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->variable($service->registerDriver($driver))->isNull();

        $this->boolean($service->set('key', 'valeur'))->isTrue();

        $this->mock($driver)->call('set')->once();
           
    }


    public function testExists()
    {
        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->exists = true;
        $driver->getMockController()->getName = 'test';

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->variable($service->registerDriver($driver))->isNull();

        $this->boolean($service->exists('key'))->isTrue();

        $this->mock($driver)->call('exists')->once();
           
    }


    public function testRemove()
    {
        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->remove = true;
        $driver->getMockController()->getName = 'test';

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->variable($service->registerDriver($driver))->isNull();

        $this->boolean($service->remove('key'))->isTrue();

        $this->mock($driver)->call('remove')->once();
           
    }


    public function testClean()
    {
        $driver = new mock\Mactronique\PhpCache\Driver\Driver;
        $driver->getMockController()->checkDriver = true;
        $driver->getMockController()->clean = true;
        $driver->getMockController()->getName = 'test';

        $service = new \Mactronique\PhpCache\Service\PhpCache();

        $this->variable($service->registerDriver($driver))->isNull();

        $this->boolean($service->clean())->isTrue();

        $this->mock($driver)->call('clean')->once();
           
    }
}
