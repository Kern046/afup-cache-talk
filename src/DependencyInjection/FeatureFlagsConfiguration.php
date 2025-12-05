<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class FeatureFlagsConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('feature_flags');

        $treeBuilder->getRootNode()
            ->useAttributeAsKey('name')
            ->booleanPrototype()->defaultFalse()->end()
        ;

        return $treeBuilder;
    }
}