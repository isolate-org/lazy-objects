<?php

namespace Isolate\LazyObjects\Tests\Object\Property;

use Isolate\LazyObjects\Object\Property\Initializer;
use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\LazyObjects\Tests\Double\EntityFake;

class InitializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Initializer
     */
    private $initializer;

    function setUp()
    {
        $this->initializer = new Initializer();
    }

    function test_property_initialization()
    {
        $entity = new EntityFake();

        $lazyProperty = new LazyProperty(new Name('items'), new EntityFake\IncrementationInitializerStub());
        $this->initializer->initialize(
            [$lazyProperty],
            '__construct',
            $entity
        );

        $this->assertSame(1, $entity->getItems());
    }

    function test_that_property_is_initialized_only_once()
    {
        $entity = new EntityFake();

        $lazyProperty = new LazyProperty(new Name('items'), new EntityFake\IncrementationInitializerStub());
        $this->initializer->initialize(
            [$lazyProperty],
            '__construct',
            $entity
        );
        $this->initializer->initialize(
            [$lazyProperty],
            '__construct',
            $entity
        );

        $this->assertSame(1, $entity->getItems());
    }

    function test_that_property_is_not_initialized_when_trigger_method_is_not_specified_in_property()
    {
        $entity = new EntityFake(0);

        $lazyProperty = new LazyProperty(
            new Name('items'),
            new EntityFake\IncrementationInitializerStub(),
            [new Method('getItems')]
        );
        $this->initializer->initialize(
            [$lazyProperty],
            '__construct',
            $entity
        );

        $this->assertSame(0, $entity->getItems());
    }

    function test_that_property_is_not_initialized_by_trigger_method()
    {
        $entity = new EntityFake(0);

        $lazyProperty = new LazyProperty(
            new Name('items'),
            new EntityFake\IncrementationInitializerStub(),
            [new Method('getItems')]
        );
        $this->initializer->initialize(
            [$lazyProperty],
            'getItems',
            $entity
        );

        $this->assertSame(1, $entity->getItems());
    }

    function test_executing_callback_after_property_initialization()
    {
        $entity = new EntityFake(0);

        $lazyProperty = new LazyProperty(
            new Name('items'),
            new EntityFake\IncrementationInitializerStub()
        );
        $initializationCallback = new EntityFake\InitializationCallbackMock();
        $lazyProperty->setInitializationCallback($initializationCallback);

        $this->initializer->initialize(
            [$lazyProperty],
            '__construct',
            $entity
        );

        $this->assertTrue($initializationCallback->wasExecuted());
    }
}
