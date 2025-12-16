<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\Discount;
use App\Entity\Product\ProductModel;
use App\Enum\FeatureFlag;
use App\Service\FeatureFlagService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Cache;
use Doctrine\Persistence\ManagerRegistry;

class DiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private FeatureFlagService $featureFlagService)
    {
        parent::__construct($registry, Discount::class);
    }

    public function getAllActiveDiscounts(): array
    {
        $qb = $this->createQueryBuilder('d')
            ->leftJoin('d.tags', 't')
            ->andWhere('d.startedAt < CURRENT_TIMESTAMP()')
            ->andWhere('d.endedAt > CURRENT_TIMESTAMP()')
            ->orderBy('d.startedAt', 'DESC');

        if ($this->featureFlagService->isEnabled(FeatureFlag::EnableDoctrineSecondLevelCache)) {
            $qb
                ->setCacheable(true)
                ->setCacheMode(Cache::MODE_NORMAL)
                ->setLifetime(3600)
                ->setCacheRegion('discounts');
        }

        $query = $qb->getQuery();

        if ($this->featureFlagService->isEnabled(FeatureFlag::EnableDoctrineResultCache)) {
            $query->enableResultCache(3600, 'active_discounts');
        }

        return $query->getResult();
    }

    public function findHighestActiveDiscountForModel(ProductModel $model): Discount|null
    {
        $qb =  $this->createQueryBuilder('d');

        $qb
            ->leftJoin('d.tags', 't')
            ->leftJoin(
                ProductModel::class,
                'm',
                Join::WITH,
                $qb->expr()->isMemberOf('t', 'm.tags'),
            )
            ->andWhere('d.startedAt < CURRENT_TIMESTAMP()')
            ->andWhere('d.endedAt > CURRENT_TIMESTAMP()')
            ->andWhere('m = :model')
            ->orderBy('d.percentage', 'DESC')
            ->setMaxResults(1)
            ->setParameter('model', $model)
        ;

        if ($this->featureFlagService->isEnabled(FeatureFlag::EnableDoctrineSecondLevelCache)) {
            $qb
                ->setCacheable(true)
                ->setCacheMode(Cache::MODE_NORMAL)
                ->setCacheRegion('discounts')
                ->setLifetime(6000)
            ;
        }

        $query = $qb->getQuery();

        if ($this->featureFlagService->isEnabled(FeatureFlag::EnableDoctrineResultCache)) {
            $resultCacheId = sprintf('active_discount_for_model_%s', $model->id->toBase32());

            $query->enableResultCache(6000, $resultCacheId);
        }

        return $query->getOneOrNullResult();
    }
}