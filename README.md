# phpcache

[![Build Status](https://travis-ci.org/Mactronique/phpcache.svg?branch=master)](https://travis-ci.org/Mactronique/phpcache)

## Supported driver

- xCache
- WinCache
- Predis
- Redis
- Memcached
- Null (special for instanciate no effect cache driver)

## Install 

``` shell
php composer.phar require mactronique/phpcache "~1.0"
```

## Configuration

### for xCache

No configuration need.



### for WinCache

No configuration need.



### for Null

No configuration need.

### For Predis

``` php
$config = array(
	"host" => "127.0.0.1",
	"port" => "",
	"password" => "",
	"database" => "",
	"timeout" => 1,
	"read_write_timeout" => 1
);
```

Only `host` key is required.

## For Redis

``` php
$config = array(
	"host" => "127.0.0.1",
	"port" => "",
	"password" => "",
	"database" => "",
	"timeout" => 1
);
```

Only `host` key is required.

## For Redis

``` php
$config = array(
	"host" => "127.0.0.1",
	"port" => 11211,
	"sharing" => 100
);
```

Only `host` key is required.


# Example of use NullDriver

This code is example of service class

``` php
use Mactronique\PhpCache\Service\PhpCache;
use Mactronique\PhpCache\Driver\NullDriver;

class myService
{
	private $cache
	public function __construct(PhpCache $cache = null)
	{
		if (null === $cache) {
			$cache = new PhpCache();
			$cache->registerDriver(new NullDriver());
		}
		$this->cache = $cache;
	}

	public function myAction()
	{
		/*
		You can use the cache but never key exist and all get return null.
		*/
		$val = $this->cache->get('key');
		[...]
	}
}

```


