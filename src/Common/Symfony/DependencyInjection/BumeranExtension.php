<?php

namespace Bumeran\Common\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class BumeranExtension
 *
 * @package Bumeran\Common\Symfony\DependencyInjection
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class BumeranExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bumeran.profiler.class', $config['profiler']['class']);
        $container->setParameter('bumeran.profiler.dsn', $config['profiler']['dsn']);
        $container->setParameter('bumeran.profiler.username', $config['profiler']['username']);
        $container->setParameter('bumeran.profiler.password', $config['profiler']['password']);
        $container->setParameter('bumeran.profiler.ttl', $config['profiler']['ttl']);
    }
}
