<?php

declare(strict_types=1);

namespace Ydee\IntlRegion\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Ydee\IntlRegion\DependencyInjection\IntlRegionExtension;

/**
 * Symfony bundle for Ydee Intl Region.
 * 
 * This bundle provides integration with Symfony applications,
 * auto-wiring the RegionProvider service and allowing configuration
 * of default locale and other settings.
 */
class IntlRegionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension(): IntlRegionExtension
    {
        if (null === $this->extension) {
            $this->extension = new IntlRegionExtension();
        }

        return $this->extension;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
} 