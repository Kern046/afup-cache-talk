<?php

declare(strict_types=1);

namespace App\DataFixtures\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class UserFactory extends PersistentProxyObjectFactory
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
        parent::__construct();
    }

    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'password' => 'test',
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'phoneNumber' => self::faker()->phoneNumber(),
            'address' => self::faker()->streetAddress(),
            'city' => self::faker()->city(),
            'postalCode' => self::faker()->postcode(),
            'country' => self::faker()->country(),
            'createdAt' => new \DateTimeImmutable(),
            'updatedAt' => new \DateTimeImmutable(),
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(function (User $user) {
            $user->password = $this->userPasswordHasher->hashPassword($user, $user->getPassword());
        });
    }

    public static function class(): string
    {
        return User::class;
    }
}