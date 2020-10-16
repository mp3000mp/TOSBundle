<?php

namespace mp3000mp\TOSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('mp3000mp_tos');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->arrayNode('doctrine')
            ->children()
            ->arrayNode('user')
            ->children()
            ->scalarNode('resolve_from')
            ->end()
            ->scalarNode('resolve_to')
            ->end()
            ->end()
            ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
