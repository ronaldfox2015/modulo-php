<?php

namespace Bumeran\Common\Symfony\DependencyInjection\CompilerPass;

use Bumeran\Common\Symfony\Profiler\Profiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ProfilerCompilerPass
 *
 * @package Bumeran\Common\Symfony\DependencyInjection\CompilerPass
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class ProfilerCompilerPass implements CompilerPassInterface
{
    /**
     * Override profiler storage
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (! $container->hasDefinition('profiler')) {
            // en caso que no exista profiler
            // se omite el override de la definicion.
            return;
        }

        $definition = $container->getDefinition('profiler');

        $definition->addArgument('%bumeran.profiler.class%');
        $definition->addArgument('%bumeran.profiler.dsn%');
        $definition->addArgument('%bumeran.profiler.username%');
        $definition->addArgument('%bumeran.profiler.password%');
        $definition->addArgument('%bumeran.profiler.ttl%');

        $definition->setClass(Profiler::class);
    }
}
