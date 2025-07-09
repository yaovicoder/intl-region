<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class for IntlRegionBundle.
 * 
 * This class defines the configuration tree for the bundle,
 * allowing users to configure default locale and other settings.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ydee_intl_region');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('default_locale')
                    ->defaultValue('en')
                    ->info('Default locale for country names')
                    ->example('en, fr, de, es')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
} 