<?php

namespace Sip\ParserCommand\Service\Interfaces;

interface SaveDataInterface
{
    public function save(string $url, string $urlHash, float $executionTime, int $deep, int $imagesLength): bool;
}
