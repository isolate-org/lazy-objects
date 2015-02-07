<?php

namespace Isolate\LazyObjects\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Isolate\LazyObjects\Proxy\ClassName;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\LazyObjects\Tests\Double\EntityFake;
use Isolate\LazyObjects\Wrapper;
use ProxyManager\Configuration;
use Symfony\Component\Filesystem\Filesystem;

class WrapperTest extends \PHPUnit_Framework_TestCase
{
    private $proxiesTargetDir;

    function setUp()
    {
        $this->proxiesTargetDir = TESTS_TEMP_DIR . '/proxy';
        $fs = new Filesystem();
        $fs->remove($this->proxiesTargetDir);
        $fs->mkdir($this->proxiesTargetDir);
    }

    function test_initializing_property_value_in_proxy_constructor()
    {
        $expectedResults = ["foo", "bar", "baz"];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)), [
                new LazyProperty(new Name("items"), new EntityFake\ItemsValueInitilizer($expectedResults))
            ]
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $this->assertInstanceOf(get_class($entity), $proxy);
        $this->assertSame($expectedResults, $proxy->getItems());
        $this->assertSame($expectedResults, $proxy->getWrappedObject()->getItems());
    }

    function test_initializing_property_value_by_add_method()
    {
        $expectedResults = ["foo", "bar", "baz", "foz"];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)), [
                new LazyProperty(
                    new Name("items"),
                    new EntityFake\ItemsValueInitilizer(["foo", "bar", "baz"]),
                    [new Method("addItem"), new Method("removeItem"), new Method("getItems")]
                )
            ]
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);
        $proxy->addItem("foz");

        $this->assertInstanceOf(get_class($entity), $proxy);
        $this->assertSame($expectedResults, $proxy->getItems());
        $this->assertSame($expectedResults, $proxy->getWrappedObject()->getItems());
    }

    function test_initializing_property_value_by_remove_method()
    {
        $expectedResults = new ArrayCollection(["foo", "bar"]);
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)), [
                new LazyProperty(
                    new Name("items"),
                    new EntityFake\ItemsValueInitilizer(new ArrayCollection(["foo", "bar", "baz"])),
                    [new Method("addItem"), new Method("removeItem"), new Method("getItems")]
                )
            ]
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);
        $proxy->removeItem("baz");

        $this->assertInstanceOf(get_class($entity), $proxy);
        $this->assertEquals($expectedResults, $proxy->getItems());
        $this->assertEquals($expectedResults, $proxy->getWrappedObject()->getItems());
    }

    /**
     * @param $entityProxyDefinition
     * @return Wrapper
     */
    private function createWrapper(Definition $entityProxyDefinition)
    {
        $configuration = new Configuration();
        $configuration->setProxiesTargetDir($this->proxiesTargetDir);
        spl_autoload_register($configuration->getProxyAutoloader());

        $lazyObjectFactory = new Factory\LazyObjectsFactory($configuration);

        return new Wrapper(new Factory($lazyObjectFactory), [$entityProxyDefinition]);
    }
}
