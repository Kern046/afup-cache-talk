<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Product\Product;
use App\Entity\Product\ProductState;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class ProductFactory extends PersistentProxyObjectFactory
{
    protected function defaults(): array|callable
    {
        return [
            'model' => ProductModelFactory::randomOrCreate(),
            'state' => self::faker()->randomElement(ProductState::cases()),
            'createdAt' => new \DateTimeImmutable(),
            'updatedAt' => new \DateTimeImmutable(),
        ];
    }

    public static function class(): string
    {
        return Product::class;
    }
}