<?php

declare(strict_types=1);

namespace App\Entity\Product;

enum ProductState: string
{
    case VeryBad = 'very_bad';
    case Bad = 'bad';
    case Fair = 'fair';
    case Good = 'good';
    case New = 'new';

    public function getPricePercentageModifier(): int
    {
        return match ($this) {
            self::VeryBad => -30,
            self::Bad => -20,
            self::Fair => -10,
            self::Good => 0,
            self::New => 10,
        };
    }
}