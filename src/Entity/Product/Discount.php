<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Discount
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid')]
    public Uuid $id;

    public function __construct(
        #[ORM\Column(length: 128)]
        public string $title,
        #[ORM\Column(type: 'text')]
        public string $description,
        #[ORM\ManyToMany(targetEntity: Tag::class)]
        public Collection $tags = new ArrayCollection(),
        #[ORM\Column]
        public int $percentage,
        #[ORM\Column(nullable: true)]
        public float|null $minimumPrice = null,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $startedAt,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $endedAt,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $updatedAt,
    ) {
    }
}