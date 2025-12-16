<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\Product;
use App\Entity\Product\ProductModel;
use App\Entity\Product\ProductState;
use App\Enum\FeatureFlag;
use App\Service\FeatureFlagService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry, private FeatureFlagService $featureFlagService)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return array<array{state: ProductState, count: int}>
     */
    public function countProductsByModelAndState(ProductModel $productModel): array
    {
        $resultCacheId = sprintf('product_counts_by_state_%s', $productModel->id->toBase32());

        $query = $this->createQueryBuilder('p')
            ->select('p.state, COUNT(p.id) as count')
            ->groupBy('p.state')
            ->getQuery();

        if ($this->featureFlagService->isEnabled(FeatureFlag::EnableDoctrineResultCache)) {
            $query->enableResultCache(3600, $resultCacheId);
        }

        return $query->getResult();
    }

    public function searchAvailableProducts(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.availableAt <= :endDate')
            ->andWhere('p.availableAt >= :startDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        ;
    }
}