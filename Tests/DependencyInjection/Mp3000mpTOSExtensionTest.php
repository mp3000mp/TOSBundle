<?php

namespace Mp3000mp\TOSBundle\Tests\DependencyInjection;

use Mp3000mp\TOSBundle\DependencyInjection\Mp3000mpTOSExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class Mp3000mpTOSExtensionTest extends TestCase
{
    public static function parameterValues(): array
    {
        return [
            ['mp3000mp_tos.kernel_event.authenticators', ['main']],
            ['mp3000mp_tos.kernel_event.priority', -20],
            ['mp3000mp_tos.user_provider', 'App\\Entity\\User'],
        ];
    }

    /**
     * @dataProvider parameterValues
     */
    public function testLoad(string $name, $expected): void
    {
        $extension = new Mp3000mpTOSExtension();
        $dataConfig = [
            'kernel_event' => [
                'authenticators' => ['main'],
                'priority' => -20,
            ],
            'user_provider' => 'App\\Entity\\User',
        ];

        $extension->load([$dataConfig], $container = $this->getContainer());

        self::assertEquals($expected, $container->getParameter($name));
    }

    private function getContainer(): ContainerBuilder
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
