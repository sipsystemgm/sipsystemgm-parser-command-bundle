<?php

namespace Sip\ParserCommand\Message;

final class ParserQueueMessage
{
    private string $url;
    private int $deep;
    private int $maxDeep;
    private int $maxPages;
    private bool $isNoHrefSubDomain = false;
    private bool $isNoImageSubDomain = false;

    public function __construct(
        string $url,
        int $deep,
        int $maxDeep,
        int $maxPages,
        bool $isNoHrefSubDomain = false,
        bool $isNoImageSubDomain = false
    ) {
        $this->isNoHrefSubDomain = $isNoHrefSubDomain;
        $this->isNoImageSubDomain = $isNoImageSubDomain;
        $this->url = $url;
        $this->deep = $deep;
        $this->maxDeep = $maxDeep;
        $this->maxPages = $maxPages;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDeep(): int
    {
        return $this->deep;
    }

    public function getMaxDeep(): int
    {
        return $this->maxDeep;
    }

    public function getMaxPages(): int
    {
        return $this->maxPages;
    }

    public function isNoHrefSubDomain(): bool
    {
        return $this->isNoHrefSubDomain;
    }

    public function isNoImageSubDomain(): bool
    {
        return $this->isNoImageSubDomain;
    }
}

