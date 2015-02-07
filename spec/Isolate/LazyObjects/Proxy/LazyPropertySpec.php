<?php

namespace spec\Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\LazyObjects\Proxy\Property\ValueInitializer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LazyPropertySpec extends ObjectBehavior
{
    function it_throws_exception_when_triggers_are_not_valid_methods(ValueInitializer $initializer)
    {
        $this->shouldThrow(new InvalidArgumentException("Each trigger must be an instance of Isolate\\LazyObjects\\Proxy\\Method"))
            ->during("__construct", [new Name("items"), $initializer, [new \DateTime()]]);
    }

    function it_knows_which_methods_triggers_initializer(ValueInitializer $initializer)
    {
        $this->beConstructedWith(new Name("items"), $initializer, [new Method("getItems")]);

        $this->isTriggeredBy("getItems")->shouldReturn(true);
        $this->hasTriggers()->shouldReturn(true);
    }
}
