<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\FeatureFlag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class FeatureFlagService
{
    public function __construct(
        #[Autowire('%app.feature_flags%')]
        private array $featureFlags,
    ) {
    }

    public function isEnabled(FeatureFlag $featureFlag): bool
    {
        return $this->featureFlags[$featureFlag->value] ?? false;
    }
}