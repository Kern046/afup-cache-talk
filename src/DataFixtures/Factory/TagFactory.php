<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\Product\Tag;
use Symfony\Component\String\Slugger\SluggerInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

class TagFactory extends PersistentObjectFactory
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
        parent::__construct();
    }

    protected function defaults(): array|callable
    {
        $label = self::faker()->unique()->word();

        return [
            'label' => $label,
            'slug' => $this->slugger->slug($label)->toString(),
            'createdAt' => new \DateTimeImmutable(),
            'updatedAt' => new \DateTimeImmutable(),
        ];
    }

    public static function class(): string
    {
        return Tag::class;
    }
}