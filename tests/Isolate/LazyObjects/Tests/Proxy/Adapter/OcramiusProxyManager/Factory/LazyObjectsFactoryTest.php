<?php

namespace Isolate\LazyObjects\Tests\Proxy\Adapter\OcramiusProxyManager\Factory;

use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory\LazyObjectsFactory;
use Isolate\LazyObjects\Tests\Double\GeneratorStrategy\FileWriterStrategyCounter;
use ProxyManager\Configuration;
use ProxyManager\FileLocator\FileLocator;
use Symfony\Component\Filesystem\Filesystem;

class LazyObjectsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var FileWriterStrategyCounter
     */
    private $generatorStrategy;

    public function setUp()
    {
        $proxyTargetDir = sys_get_temp_dir() . '/LazyObjectsFactory';
        $fs = new Filesystem();
        $fs->remove($proxyTargetDir);
        $fs->mkdir($proxyTargetDir);

        $this->configuration = new Configuration();
        $this->configuration->setProxiesTargetDir($proxyTargetDir);
        $this->generatorStrategy = new FileWriterStrategyCounter(
            new FileLocator($this->configuration->getProxiesTargetDir())
        );
        $this->configuration->setGeneratorStrategy($this->generatorStrategy);
    }

    public function test_always_generating_strategy()
    {
        $factory = new LazyObjectsFactory($this->configuration, LazyObjectsFactory::GENERATE_ALWAYS);
        $factory->createProxyClass("Isolate\\LazyObjects\\Tests\\Double\\EntityFake");

        // we need to create new LazyObjectsFactory in order to prevent internal factory cache
        $factory = new LazyObjectsFactory($this->configuration, LazyObjectsFactory::GENERATE_ALWAYS);
        $className = $factory->createProxyClass("Isolate\\LazyObjects\\Tests\\Double\\EntityFake");

        $this->assertSame(2, $this->generatorStrategy->getClassGenerationCount($className));
    }

    public function test_when_not_exists_generating_strategy()
    {
        $factory = new LazyObjectsFactory($this->configuration, LazyObjectsFactory::GENERATE_ALWAYS);
        $factory->createProxyClass("Isolate\\LazyObjects\\Tests\\Double\\EntityFake");

        // we need to create new LazyObjectsFactory in order to prevent internal factory cache
        $factory = new LazyObjectsFactory($this->configuration, LazyObjectsFactory::GENERATE_WHEN_NOT_EXISTS);
        $className = $factory->createProxyClass("Isolate\\LazyObjects\\Tests\\Double\\EntityFake");

        $this->assertSame(1, $this->generatorStrategy->getClassGenerationCount($className));
    }

    public function test_never_generating_strategy()
    {
        $factory = new LazyObjectsFactory($this->configuration, LazyObjectsFactory::GENERATE_ALWAYS);
        $factory->createProxyClass("Isolate\\LazyObjects\\Tests\\Double\\EntityFake");
        $this->generatorStrategy->reset();

        // we need to create new LazyObjectsFactory in order to prevent internal factory cache
        $factory = new LazyObjectsFactory($this->configuration, LazyObjectsFactory::GENERATE_NEVER);
        $className = $factory->createProxyClass("Isolate\\LazyObjects\\Tests\\Double\\EntityFake");

        $this->assertSame(0, $this->generatorStrategy->getClassGenerationCount($className));
    }
}
