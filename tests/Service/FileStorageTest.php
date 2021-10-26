<?php

namespace Sip\ParserCommand\Tests\Service;


use Sip\ParserCommand\Service\FileStorage;
use Sip\ReaderManager\Interfaces\ReaderStorageInterface;

class FileStorageTest extends \Sip\ReaderManager\Test\FileStorageTest
{
    protected function getStorage(): ReaderStorageInterface
    {
        return new FileStorage('test-data.txt');
    }
}
