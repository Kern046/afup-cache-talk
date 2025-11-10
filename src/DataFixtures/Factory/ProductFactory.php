<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Product;
use Symfony\Component\String\Slugger\SluggerInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class ProductFactory extends PersistentProxyObjectFactory
{
    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
        parent::__construct();
    }

    protected function defaults(): array|callable
    {
        $name = 'Planche n°' . self::faker()->unique()->numberBetween(1, 100);

        return [
            'name' => $name,
            'slug' => $this->slugger->slug($name),
            'description' => self::faker()->text(),
            'rentPrice' => self::faker()->numberBetween(10, 60),
            'availableQuantity' => self::faker()->numberBetween(1, 100),
            'createdAt' => new \DateTimeImmutable(),
            'updatedAt' => new \DateTimeImmutable(),
        ];
    }

    public static function class(): string
    {
        return Product::class;
    }
}