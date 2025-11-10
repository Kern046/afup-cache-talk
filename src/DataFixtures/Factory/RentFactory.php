<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Rent;
use App\Enum\RentStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class RentFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array|callable
    {
        return [
            'user' => UserFactory::random(),
            'product' => ProductFactory::random(),
            'startDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 month', '+1 month')),
            'endDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('+1 month', '+2 month')),
            'price' => self::faker()->randomFloat(2, 10, 1000),
            'status' => RentStatus::Rented,
        ];
    }

    public static function class(): string
    {
        return Rent::class;
    }
}