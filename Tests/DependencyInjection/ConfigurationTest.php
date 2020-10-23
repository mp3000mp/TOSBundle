<?php

namespace Mp3000mp\TOSBundle\Tests\DependencyInjection;

use Mp3000mp\TOSBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Processor
     */
    private $processor;

    public function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function testCustomConfig(): void
    {
        $dataConfig = [
            'user_provider' => 'App\\Entity\\User',
        ];

        $treeBuilder = $this->configuration->getConfigTreeBuilder();
        $config = $this->processor->processConfiguration($this->configuration, ['mp3000mp_tos' => $dataConfig]);

        self::assertInstanceOf(ConfigurationInterface::class, $this->configuration);
        self::assertInstanceOf(TreeBuilder::class, $treeBuilder);

        self::assertEquals($dataConfig, $config);
    }
}
