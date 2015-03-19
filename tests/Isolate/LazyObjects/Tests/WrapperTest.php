<?php

namespace Isolate\LazyObjects\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Isolate\LazyObjects\Proxy\ClassName;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\MethodReplacement;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\LazyObjects\Tests\Double\EntityFake;
use Isolate\LazyObjects\Wrapper;

class WrapperTest extends \PHPUnit_Framework_TestCase
{
    function test_initializing_property_value_in_proxy_constructor()
    {
        $expectedResults = ["foo", "bar", "baz"];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            [
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

    function test_getting_lazy_properties_from_proxy()
    {
        $entity = new EntityFake();
        $lazyProperty = new LazyProperty(
            new Name("items"),
            $this->getMock('Isolate\\LazyObjects\\Proxy\\Property\\ValueInitializer')
        );

        $entityProxyDefinition = new Definition(new ClassName(get_class($entity)), [$lazyProperty]);

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $this->assertEquals([$lazyProperty], $proxy->getLazyProperties());
    }

    function test_replacing_method()
    {
        $expectedResult = ['foo', 'bar', 'baz'];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            [
                new LazyProperty(
                    new Name("items"),
                    new EntityFake\ItemsValueInitilizer($expectedResult),
                    [new Method("getItems")]
                )
            ],
            [new MethodReplacement(new Method("getItems"), new EntityFake\GetItemsReplacement($expectedResult))]
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $this->assertSame([], $entity->getItems());
        $this->assertSame($expectedResult, $proxy->getItems());
        $this->assertSame($expectedResult, $proxy->getWrappedObject()->getItems());
    }

    function test_lazy_property_initialization_with_method_replacement()
    {
        $replacementResult = ['foo', 'bar', 'baz'];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            [],
            [new MethodReplacement(new Method("getItems"), new EntityFake\GetItemsReplacement($replacementResult))]
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $this->assertSame([], $entity->getItems());
        $this->assertSame($replacementResult, $proxy->getItems());
    }

    function test_lazy_objects_serialization()
    {
        $replacementResult = ['foo', 'bar', 'baz'];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            [],
            [new MethodReplacement(new Method("getItems"), new EntityFake\GetItemsReplacement($replacementResult))]
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $serialized = serialize($proxy);
        $unserialized = unserialize($serialized);

        $this->assertSame($replacementResult, $unserialized->getItems());
    }


    /**
     * @param $entityProxyDefinition
     * @return Wrapper
     */
    private function createWrapper(Definition $entityProxyDefinition)
    {
        $lazyObjectFactory = new Factory\LazyObjectsFactory();

        return new Wrapper(new Factory($lazyObjectFactory), [$entityProxyDefinition]);
    }
}
