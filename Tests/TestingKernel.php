<?php

namespace mp3000mp\TOSBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use mp3000mp\TOSBundle\Controller\Mp3000mpTOSController;
use mp3000mp\TOSBundle\Mp3000mpTOSBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Security\Core\User\UserInterface;

class TestingKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles()
    {
        return [
            new Mp3000mpTOSBundle(),
            new DoctrineBundle(),
            new FrameworkBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/../Resources/config/services.xml');
        $loader->load(__DIR__.'/../Resources/config/test.yaml');
    }

    protected function configureContainer(ContainerConfigurator $c): void
    {
        // PHP equivalent of config/packages/framework.yaml
        $c->extension('framework', [
            'test' => true,
            'secret' => 'S0ME_SECRET',
        ]);
        $c->extension('doctrine', [

        ]);
        $c->extension('mp3000mp_tos', [
            'doctrine' => [
                'user' => [
                    'resolve_to' => UserInterface::class,
                ],
            ],
        ]);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('mp3000mp_tos', '/tos/')->controller([Mp3000mpTOSController::class, 'index']);
    }

}
