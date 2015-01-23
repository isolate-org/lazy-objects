<?php

namespace Isolate\LazyObjects\Tests;

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
                new Method("getItems", new EntityFake\GetItemReplacementStub($expectedResults))
            ])
        );

        $wrapper = new Wrapper(new Factory(), [$entityProxyDefinition]);
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
                new Method("getItems", new EntityFake\GetItemReplacementStub([]))
            ])
        );

        $wrapper = new Wrapper(new Factory(), [$entityProxyDefinition]);
        $proxy = $wrapper->wrap($entity);

        $this->assertInstanceOf("Isolate\\LazyObjects\\WrappedObject", $proxy);
    }

    function test_getting_wrapped_object()
    {
        $entity = new EntityFake();

        $entityProxyDefinition = new Definition(
            new ClassName(get_class($entity)),
            new Methods([
                new Method("getItems", new EntityFake\GetItemReplacementStub([]))
            ])
        );

        $wrapper = new Wrapper(new Factory(), [$entityProxyDefinition]);

        /* @var \Isolate\LazyObjects\WrappedObject $proxy*/
        $proxy = $wrapper->wrap($entity);
        $this->assertSame($proxy->getWrappedObject(), $entity);
    }
}
