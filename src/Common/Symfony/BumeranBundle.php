<?php

namespace Bumeran\Common\Symfony;

use Bumeran\Common\Symfony\DependencyInjection\CompilerPass\DomainEventsPass;
use Bumeran\Common\Symfony\DependencyInjection\CompilerPass\ProfilerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Class Bundle
 *
 * @package Bumeran\Common\Symfony
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class BumeranBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ProfilerCompilerPass());
        $container->addCompilerPass(new DomainEventsPass());

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/Resources/config/'));
        $loader->load('services.yml');
    }

    public function boot()
    {
        parent::boot();

        // Get logger
        $logger = $this->container->get('logger');

        // Set eventmanager
        $eventManager = $this->container->get('apt.domain_event_manager');
        $eventManager->setLogger($logger);
    }
}
