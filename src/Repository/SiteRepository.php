<?php

namespace Sip\ParserCommand\Repository;

use Sip\ParserCommand\Entity\Site;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class SiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Site::class);
    }

    public function getSiteByUrl(string $url): Site
    {
        $result = $this->findOneByUrl($url);
        if (empty($result)) {
            $result = new Site();
        }
        return $result;
    }

    public function getItemsQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('s');
    }
}

