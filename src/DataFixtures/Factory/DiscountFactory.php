<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Product\Discount;
use Doctrine\Common\Collections\ArrayCollection;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

class DiscountFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array|callable
    {
        $year = (new \DateTimeImmutable())->format('Y');
        $month = self::faker()->month();
        $monthName = (new \DateTimeImmutable())->setDate((int) $year, (int) $month, 1)->format('M');

        $startedAt = (new \DateTimeImmutable())->setDate((int) $year, (int) $month, 1);
        $endedAt = (new \DateTimeImmutable())->setDate((int) $year, (int) $month, 1)->modify('last day of this month');

        return [
            'title' => 'Promos du mois de ' . $monthName . ' ' . $year,
            'description' => 'Promos du mois de ' . $monthName . ' ' . $year,
            'tags' => new ArrayCollection(TagFactory::randomRange(0, 4)),
            'percentage' => self::faker()->randomElement([5, 10, 15, 20]),
            'minimumPrice' => self::faker()->randomElement([null, self::faker()->randomNumber(1) * 10]),
            'startedAt' => $startedAt,
            'endedAt' => $endedAt,
            'createdAt' => new \DateTimeImmutable(),
            'updatedAt' => new \DateTimeImmutable(),
        ];
    }

    public static function class(): string
    {
        return Discount::class;
    }
}