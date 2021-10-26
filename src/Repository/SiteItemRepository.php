<?php

namespace Sip\ParserCommand\Repository;

use Sip\ParserCommand\Entity\Site;
use Sip\ParserCommand\Entity\SiteItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class SiteItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteItem::class);
    }

    public function getByUrlHash(string $urlHash) : SiteItem
    {
        $result = $this->findOneByUrlHash($urlHash);
        if (empty($result)) {
            $result = new SiteItem();
        }
        return $result;
    }

    public function getItemsQueryBuilderBySiteEntity(Site $site): QueryBuilder
    {
        return $this
            ->createQueryBuilder('si')
            ->innerJoin('si.site', 'site')
            ->addSelect('site')
            ->andWhere('site=:site')
            ->setParameter('site', $site)
        ;
    }
}

