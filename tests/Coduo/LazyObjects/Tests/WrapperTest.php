<?php

namespace Coduo\LazyObjects\Tests;

use Coduo\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Coduo\LazyObjects\Proxy\ClassName;
use Coduo\LazyObjects\Proxy\Definition;
use Coduo\LazyObjects\Proxy\Method;
use Coduo\LazyObjects\Proxy\Methods;
use Coduo\LazyObjects\Tests\Double\EntityFake;
use Coduo\LazyObjects\Wrapper;

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

        $this->assertInstanceOf("Coduo\\LazyObjects\\WrappedObject", $proxy);
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

        /* @var \Coduo\LazyObjects\WrappedObject $proxy*/
        $proxy = $wrapper->wrap($entity);
        $this->assertSame($proxy->getWrappedObject(), $entity);
    }
}
