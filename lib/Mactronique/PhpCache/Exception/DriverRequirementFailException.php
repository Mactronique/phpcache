<?php

namespace Mactronique\PhpCache\Exception;

class DriverRequirementFailException extends PhpCacheException
{
    private $driverName;

    public function __construct($message, $driverName)
    {
        parent::__construct($message);
        $this->driverName = $driverName;
    }

    public function getDriverName()
    {
        return $this->driverName;
    }
}
