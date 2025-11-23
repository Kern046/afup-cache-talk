<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Product\ProductModel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\String\Slugger\SluggerInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

class ProductModelFactory extends PersistentProxyObjectFactory
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
            'tags' => new ArrayCollection(TagFactory::randomRange(0, 4)),
            'rentPrice' => self::faker()->numberBetween(10, 60),
        ];
    }

    public static function class(): string
    {
        return ProductModel::class;
    }
}