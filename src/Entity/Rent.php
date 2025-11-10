<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\RentStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Rent
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid')]
    public Uuid $id;

    public function __construct(
        #[ORM\ManyToOne]
        public User $user,
        #[ORM\ManyToOne]
        public Product $product,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $startDate,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $endDate,
        #[ORM\Column(type: 'integer')]
        public int $price,
        #[ORM\Column(enumType: RentStatus::class, length: 48)]
        public RentStatus $status,
    ) {
    }
}