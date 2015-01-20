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
}
