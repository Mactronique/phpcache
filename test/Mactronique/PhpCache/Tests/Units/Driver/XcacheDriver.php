<?php

namespace Mactronique\PhpCache\Tests\Units\Driver;

use atoum;
use mock;

class XcacheDriver extends atoum
{
    public function testsName()
    {
        $obj = new \Mactronique\PhpCache\Driver\XcacheDriver();
        $this->assert->string($obj->getName())->isEqualTo('xCache');
    }
}
