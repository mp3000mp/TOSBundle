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
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('doctrine')
                ->children()
                    ->arrayNode('user')
                    ->children()
                        ->scalarNode('resolve_to')
                        ->info('The class to which the TermsOfServiceSignature entity user relationship will be mapped. '.
                            'It must implement the UserInterface Symfony\Component\Security\Core\User\UserInterface interface.')
                        ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
