<?php

namespace mp3000mp\TOSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Security\Core\User\UserInterface;

class Mp3000mpTOSExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // config
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        // services
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader->load('services.xml');
        $loader2 = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader2->load('test.yaml');

        $container->setParameter('mp3000mp_tos.doctrine.user.resolve_from', UserInterface::class);
        $container->setParameter('mp3000mp_tos.doctrine.user.resolve_to', $config['doctrine']['user']['resolve_to']);
    }
}
