<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Dependency injection extension for IntlRegionBundle.
 * 
 * This extension handles the configuration and service registration
 * for the IntlRegion bundle.
 */
class IntlRegionExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // Set the default locale parameter
        $container->setParameter('ydee_intl_region.default_locale', $config['default_locale']);

        // Configure the RegionProvider service with the default locale
        $regionProviderDefinition = $container->getDefinition('ydee_intl_region.region_provider');
        $regionProviderDefinition->setArgument('$defaultLocale', $config['default_locale']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'ydee_intl_region';
    }
} 