<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid')]
    public Uuid $id;

    public function __construct(
        #[ORM\Column]
        public string $email,
        #[ORM\Column]
        public string $password,
        #[ORM\Column]
        public string $firstName,
        #[ORM\Column]
        public string $lastName,
        #[ORM\Column]
        public string $phoneNumber,
        #[ORM\Column]
        public string $address,
        #[ORM\Column]
        public string $city,
        #[ORM\Column]
        public string $postalCode,
        #[ORM\Column]
        public string $country,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'datetime_immutable')]
        public \DateTimeImmutable $updatedAt,
        #[ORM\Column(type: 'json')]
        public array $roles = [],
        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        public ?\DateTimeImmutable $deletedAt = null,
    ) {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles + ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {

    }
}