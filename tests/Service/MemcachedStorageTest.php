<?php

namespace Sip\ParserCommand\Tests\Service;

use Sip\ParserCommand\Service\MemcachedStorage;
use Sip\ReaderManager\Interfaces\ReaderStorageInterface;
use Sip\ReaderManager\Test\AbstractStorageTest;

class MemcachedStorageTest extends AbstractStorageTest
{
    protected function getStorage(): ReaderStorageInterface
    {
        return new MemcachedStorage('test_mc', [
            'host' => $_ENV['MEMCACHED_HOST'],
            'port' => $_ENV['MEMCACHED_PORT']
        ]);
    }
}

