<?php

namespace Mp3000mp\TOSBundle\Tests\DependencyInjection;

use mp3000mp\TOSBundle\DependencyInjection\Mp3000mpTOSExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class Mp3000mpTOSExtensionTest extends TestCase
{
    public static function parameterValues()
    {
        return [
            ['mp3000mp_tos.user_provider', 'App\\Entity\\User'],
        ];
    }

    /**
     * @dataProvider parameterValues
     */
    public function testLoad($name, $expected)
    {
        $extension = new Mp3000mpTOSExtension();
        $dataConfig = [
            'user_provider' => $expected
        ];

        $extension->load([$dataConfig], $container = $this->getContainer());

        self::assertEquals($expected, $container->getParameter($name));
    }

    private function getContainer()
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.debug' => false,
            'kernel.bundles' => [],
            'kernel.cache_dir' => sys_get_temp_dir(),
            'kernel.environment' => 'test',
            'kernel.root_dir' => __DIR__.'/../../',
        ]));
    }
}
