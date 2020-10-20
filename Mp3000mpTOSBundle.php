<?php

namespace mp3000mp\TOSBundle;

use mp3000mp\TOSBundle\DependencyInjection\Compiler\DoctrineResolveTargetEntityPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Mp3000mpTOSBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        //$container->addCompilerPass(new DoctrineResolveTargetEntityPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1000);
    }
}
