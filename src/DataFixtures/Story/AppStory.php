<?php

namespace App\DataFixtures\Story;

use App\DataFixtures\Factory\ProductFactory;
use App\DataFixtures\Factory\RentFactory;
use App\DataFixtures\Factory\UserFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        UserFactory::createMany(20);

        ProductFactory::createMany(40);

        RentFactory::createMany(10);
    }
}
