<?php

namespace Mp3000mp\TOSBundle\DependencyInjection;

use Mp3000mp\TOSBundle\EventSubscriber\TermsOfServiceSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Security\Core\User\UserInterface;

class Mp3000mpTOSExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // services
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader->load('services.xml');

        // config
        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('mp3000mp_tos.user_provider', $config['user_provider']);
        $container->setParameter('mp3000mp_tos.kernel_event.authenticators', $config['kernel_event']['authenticators']);
        $container->setParameter('mp3000mp_tos.kernel_event.priority', $config['kernel_event']['priority']);
        TermsOfServiceSubscriber::$priority = $config['kernel_event']['priority'];
    }

    public function prepend(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());

        $config = $this->processConfiguration(new Configuration(), $configs);

        // doctrine
        $doctrineConfig = [];
        $doctrineConfig['orm']['resolve_target_entities'][UserInterface::class] = $config['user_provider'];
        $doctrineConfig['orm']['mappings'][] = [
            'name' => 'Mp3000mpTOSBundle',
            'is_bundle' => true,
            'type' => 'xml',
            'prefix' => 'Mp3000mp\TOSBundle\Entity',
        ];
        $container->prependExtensionConfig('doctrine', $doctrineConfig);

        // twig
        /*$twigConfig = [];
        $twigConfig['globals']['mp3000mp_tos_bar_service'] = "@mp3000mp_tos.service";
        $twigConfig['paths'][__DIR__.'/../Resources/views'] = "mp3000mp_tos";
        $twigConfig['paths'][__DIR__.'/../Resources/public'] = "mp3000mp_tos.public";
        $container->prependExtensionConfig('twig', $twigConfig);*/
    }

    public function getAlias()
    {
        return 'mp3000mp_tos';
    }
}
