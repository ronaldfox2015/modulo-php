<?php

namespace Bumeran\Common\Symfony\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DomainEventsPass
 *
 * @package Bumeran\Common\Symfony\DependencyInjection\CompilerPass
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class DomainEventsPass implements CompilerPassInterface
{
    /**
     * Override profiler storage
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('apt.domain_event_manager')) {
            return;
        }

        $definition = $container->findDefinition('apt.domain_event_manager');
        $taggedServices = $container->findTaggedServiceIds('apt.domain_event');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('subscribe', [new Reference($id)]);
        }
    }
}
