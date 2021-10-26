<?php

namespace Sip\ParserCommand\Service;

use Sip\ParserCommand\Repository\SiteItemRepository;
use Sip\ParserCommand\Repository\SiteRepository;
use Sip\ParserCommand\Service\Interfaces\SaveDataInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveData implements SaveDataInterface
{
    private SiteRepository $siteRepository;
    private SiteItemRepository $itemRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        SiteRepository         $siteRepository,
        SiteItemRepository     $itemRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->siteRepository = $siteRepository;
        $this->itemRepository = $itemRepository;
    }

    public function save(string $url, string $urlHash, float $executionTime, int $deep, int $imagesLength): bool
    {
        if ($imagesLength <= 0) {
            return false;
        }

        try {
            $domain = parse_url($url, PHP_URL_HOST);
            $url_ = preg_replace('/\W+/', '-', $domain);

            $siteEntity = $this->siteRepository->getSiteByUrl($url_);
            $siteEntity
                ->setName($domain)
                ->setUrl($url_);

            $siteItemEntity = $this->itemRepository->getByUrlHash($urlHash);
            $siteItemEntity
                ->setUrl($url)
                ->setUrlHash($urlHash)
                ->setImagesLength($imagesLength)
                ->setExecutionTime($executionTime)
                ->setDeep($deep);

            $siteEntity->addItem($siteItemEntity);
            $this->entityManager->persist($siteEntity);
            $this->entityManager->persist($siteItemEntity);
            $this->entityManager->flush();
            $this->entityManager->clear();
            return true;
        } catch (\Throwable $exception) {
            if (strpos($exception->getMessage(), '1062 Duplicate entry') === false) {
                throw $exception;
            }
        }
        return false;
    }
}
