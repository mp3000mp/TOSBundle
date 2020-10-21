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
