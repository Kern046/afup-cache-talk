<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Product
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
        #[ORM\Column(type: 'integer')]
        public int $rentPrice,
        #[ORM\Column(type: 'integer')]
        public int $availableQuantity,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $updatedAt,
    ) {
    }
}