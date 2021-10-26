<?php

namespace Sip\ParserCommand\Service;

use Sip\ReaderManager\AbstractStorage;
use Sip\ReaderManager\Interfaces\ReadCacheInterface;

class MemcachedStorage extends AbstractStorage implements ReadCacheInterface
{
    private \Memcached $memcached;
    public string $savedLengthKey = 'length';
    public string $currentDeepKey = 'deep';
    public string $urlsKey = 'list';
    
    public function __construct(string $name, array $options)
    {
        $this->savedLengthKey .= $name;
        $this->currentDeepKey .= $name;
        $this->urlsKey .= $name;
        
        $this->memcached = new \Memcached();
        $this->memcached->addServer($options['host'], $options['port']);

        $this->currentDeep = (int)$this->memcached->get($this->currentDeepKey);
        $this->savedLength = (int)$this->memcached->get($this->savedLengthKey);
        if ($urls = $this->memcached->get($this->urlsKey)) {
            $this->urls = $urls;
        }
    }


    public function save(): self
    {
        $this->memcached->set($this->urlsKey, $this->urls);
        $this->memcached->set($this->savedLengthKey, $this->savedLength);
        $this->memcached->set($this->currentDeepKey, $this->currentDeep);
        return $this;
    }
}

