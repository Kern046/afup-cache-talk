<?php

namespace App\DataFixtures\Story;

use App\DataFixtures\Factory\DiscountFactory;
use App\DataFixtures\Factory\ProductFactory;
use App\DataFixtures\Factory\ProductModelFactory;
use App\DataFixtures\Factory\RentFactory;
use App\DataFixtures\Factory\TagFactory;
use App\DataFixtures\Factory\UserFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        UserFactory::createMany(20);

        TagFactory::createMany(10);

        ProductModelFactory::createMany(10);

        ProductFactory::createMany(400);

        RentFactory::createMany(50);

        DiscountFactory::createMany(10);
    }
}
