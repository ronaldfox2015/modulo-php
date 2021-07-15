<?php

namespace Bumeran\Common\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Bumeran\Common\Symfony\DependencyInjection
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2017, Orbis
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('library');

        $rootNode
            ->children()
              ->arrayNode('profiler')
                 ->addDefaultsIfNotSet()
                 ->children()
                    ->scalarNode('class')
                       ->defaultNull()
                    ->end()

                    ->scalarNode('dsn')
                       ->defaultNull()
                    ->end()

                    ->scalarNode('username')
                       ->defaultNull()
                    ->end()

                    ->scalarNode('password')
                       ->defaultNull()
                    ->end()

                    ->scalarNode('ttl')
                       ->defaultValue('3600')
                    ->end()
                 ->end()

            ->end();

        return $treeBuilder;
    }
}
