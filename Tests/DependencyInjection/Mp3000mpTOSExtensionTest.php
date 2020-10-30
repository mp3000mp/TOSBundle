<?php

namespace Mp3000mp\TOSBundle\Tests\DependencyInjection;

use Mp3000mp\TOSBundle\DependencyInjection\Mp3000mpTOSExtension;
use PHPUnit\Framework\MockObject\MockObject;
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
     *
     * @param mixed $expected
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

        $container = $this->getContainer();
        $extension->load([$dataConfig], $container);

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

    public function testPrepend(): void
    {
        $extension = new Mp3000mpTOSExtension();

        /**
         * @var ContainerBuilder|MockObject $container
         */
        $container = $this->getMockBuilder(ContainerBuilder::class)->disableOriginalConstructor()->getMock();
        $container->expects(self::once())
            ->method('getExtensionConfig')
            ->willReturn(['mp3000mp_tos' => ['user_provider' => 'test']]);
        $container->expects(self::once())
            ->method('prependExtensionConfig');
        $extension->prepend($container);
    }

    public function testGetAlias(): void
    {
        $extension = new Mp3000mpTOSExtension();
        self::assertEquals('mp3000mp_tos', $extension->getAlias());
    }
}
