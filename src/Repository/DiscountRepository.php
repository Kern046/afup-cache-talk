<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product\Discount;
use App\Entity\Product\ProductModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class DiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
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
            ->andWhere('d.endedAt > :now')
            ->andWhere('m = :model')
            ->orderBy('d.percentage', 'DESC')
            ->setMaxResults(1)
            ->setParameter('model', $model)
            ->setParameter('now', new \DateTimeImmutable());

        return $qb->getQuery()->getOneOrNullResult();
    }
}