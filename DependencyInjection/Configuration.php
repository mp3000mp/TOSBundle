<?php

namespace Mp3000mp\TOSBundle\DependencyInjection;

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
                ->arrayNode('kernel_event')
                    ->children()
                        ->arrayNode('authenticators')
                            ->info('List of authenticators to be impacted by terms of service.')
                            ->isRequired()
                            ->scalarPrototype()
                                ->defaultValue('main')
                            ->end()
                        ->end()
                        ->integerNode('priority')
                            ->info('Kernel event priority.')
                            ->defaultValue(-20)
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('user_provider')
                    ->info('The class to which the TermsOfServiceSignature entity user relationship will be mapped. '.
                        'It must implement the Symfony\Component\Security\Core\User\UserInterface interface.')
                    ->isRequired()
                    ->defaultValue('\App\Entity\User')
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
