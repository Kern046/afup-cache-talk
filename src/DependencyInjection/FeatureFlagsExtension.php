<?php

declare(strict_types = 1);

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class FeatureFlagsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new FeatureFlagsConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $featureFlags = [];
        foreach ($config as $name => $isEnabled) {
            $featureFlags[$name] = $isEnabled;
        }

        $container->setParameter('app.feature_flags', $featureFlags);
    }
}