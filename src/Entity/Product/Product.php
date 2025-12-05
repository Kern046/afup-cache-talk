<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Cache(usage: 'READ_ONLY', region: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid')]
    public Uuid $id;

    public function __construct(
        #[ORM\ManyToOne]
        #[ORM\Cache(usage: 'READ_ONLY', region: 'product_models_association')]
        public ProductModel $model,
        #[ORM\Column(type: 'enum')]
        public ProductState $state,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $updatedAt,
    ) {
    }
}