<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Cache(usage: 'READ_ONLY', region: 'tags')]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid')]
    public Uuid $id;

    public function __construct(
        #[ORM\Column(type: 'string', length: 64)]
        public string $label,
        #[ORM\Column(type: 'string', length: 64)]
        public string $slug,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $updatedAt,
    ) {
    }
}