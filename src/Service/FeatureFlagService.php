<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\FeatureFlag;

readonly class FeatureFlagService
{
    public function __construct(
        private array $featureFlags,
    ) {
    }

    public function isFeatureEnabled(FeatureFlag $featureFlag): bool
    {
        return $this->featureFlags[$featureFlag->value] ?? false;
    }
}