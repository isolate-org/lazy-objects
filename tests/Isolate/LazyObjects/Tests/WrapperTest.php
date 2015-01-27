<?php

namespace Isolate\LazyObjects\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Isolate\LazyObjects\Object\PropertyValueSetter;
use Isolate\LazyObjects\Object\Value\AssemblerFactory;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Isolate\LazyObjects\Proxy\ClassName;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\Methods;
use Isolate\LazyObjects\Tests\Double\EntityFake;
use Isolate\LazyObjects\Wrapper;

class WrapperTest extends \PHPUnit_Framework_TestCase
{
    function test_replacing_single_method_on_entity()
    {
        $expectedResults = ["foo", "bar", "baz"];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            new Methods([
                new Method(new Method\Name("getItems"), new EntityFake\GetItemReplacementStub($expectedResults))
            ])
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $this->assertInstanceOf(get_class($entity), $proxy);
        $this->assertSame($expectedResults, $proxy->getItems());
    }

    function test_replaced_object_instance()
    {
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            new Methods([
                new Method(new Method\Name("getItems"), new EntityFake\GetItemReplacementStub([]))
            ])
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $this->assertInstanceOf("Isolate\\LazyObjects\\WrappedObject", $proxy);
    }

    function test_getting_wrapped_object()
    {
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            new Methods([
                new Method(new Method\Name("getItems"), new EntityFake\GetItemReplacementStub([]))
            ])
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);

        /* @var \Isolate\LazyObjects\WrappedObject $proxy*/
        $proxy = $wrapper->wrap($entity);
        $this->assertSame($proxy->getWrappedObject(), $entity);
    }

    function test_replacing_single_method_on_entity_with_target_property()
    {
        $expectedResults = ["foo", "bar", "baz"];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            new Methods([
                new Method(new Method\Name("getItems"), new EntityFake\GetItemReplacementStub($expectedResults), 'items')
            ])
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);

        $this->assertInstanceOf(get_class($entity), $proxy);
        $this->assertSame($expectedResults, $proxy->getItems());
        $this->assertEqualsPropertyValue($expectedResults, $proxy->getWrappedObject(), 'items');
    }

    function test_replacing_single_method_on_entity_with_target_property_when_property_value_is_an_array_that_is_not_empty()
    {
        $replacedResults = ["foo", "bar", "baz"];
        $expectedResults = ["foo", "bar", "baz", "foz"];
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            new Methods([
                new Method(new Method\Name("getItems"), new EntityFake\GetItemReplacementStub($replacedResults), 'items')
            ])
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);
        $proxy->addItem("foz");

        $this->assertInstanceOf(get_class($entity), $proxy);
        $this->assertSame($expectedResults, $proxy->getItems());
        $this->assertEqualsPropertyValue($expectedResults, $proxy->getWrappedObject(), 'items');
    }

    function test_replacing_single_method_on_entity_with_target_property_when_property_value_is_an_traversable_array_object_that_is_not_empty()
    {
        $replacedResults = new ArrayCollection(["foo", "bar", "baz"]);
        $expectedResults = new ArrayCollection(["foo", "bar", "baz", "foz"]);
        $entity = new EntityFake(new ArrayCollection([]));

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            new Methods([
                new Method(new Method\Name("getItems"), new EntityFake\GetItemReplacementStub($replacedResults), 'items')
            ])
        );

        $wrapper = $this->createWrapper($entityProxyDefinition);
        $proxy = $wrapper->wrap($entity);
        $proxy->addItem("foz");

        $this->assertInstanceOf(get_class($entity), $proxy);
        $this->assertEquals($expectedResults, $proxy->getItems());
        $this->assertEqualsPropertyValue($expectedResults, $proxy->getWrappedObject(), 'items');
    }

    /**
     * @param mixed $expectedResult
     * @param mixed $object
     * @param string $propertyName
     */
    private function assertEqualsPropertyValue($expectedResult, $object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $itemsProperty = $reflection->getProperty($propertyName);
        $itemsProperty->setAccessible(true);
        $this->assertEquals($expectedResult, $itemsProperty->getValue($object));
    }

    /**
     * @param $entityProxyDefinition
     * @return Wrapper
     */
    private function createWrapper(Definition $entityProxyDefinition)
    {
        $propertyValueSetter = new PropertyValueSetter(new AssemblerFactory());

        return new Wrapper(new Factory($propertyValueSetter), [$entityProxyDefinition]);
    }
}
