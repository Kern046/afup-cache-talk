<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Cache(usage: 'READ_ONLY', region: 'product_models')]
class ProductModel
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid')]
    public Uuid $id;

    public function __construct(
        #[ORM\Column(length: 128)]
        public string $name,
        #[ORM\Column(length: 128, unique: true)]
        public string $slug,
        #[ORM\Column(type: 'text')]
        public string $description,
        #[ORM\ManyToMany(targetEntity: Tag::class)]
        public Collection $tags = new ArrayCollection(),
        #[ORM\Column(type: 'integer')]
        public int $rentPrice,
    ) {
    }
}