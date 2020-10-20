<?php

namespace mp3000mp\TOSBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineResolveTargetEntityPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {

        // todo pourquoi alias doctrine.orm.listeners.resolve_target_entity pas défini ?

        var_dump($container->getAliases());

        foreach ($container->getDefinitions() as $definition){
            var_dump($definition->getClass());
        }


        // resolve_target_entities
        $definition = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        $definition->addMethodCall('addResolveTargetEntity', [
            $container->getParameter('mp3000mp_tos.doctrine.user.resolve_from'),
            $container->getParameter('mp3000mp_tos.doctrine.user.resolve_to'),
            [],
        ]);

        $definition->addTag('doctrine.event_subscriber', ['connection' => 'default']);
    }
}
