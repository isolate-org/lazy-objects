<?php

namespace spec\Isolate\LazyObjects;

use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WrapperSpec extends ObjectBehavior
{
    function let(Factory $factory)
    {
        $this->beConstructedWith($factory);
    }

    function it_knows_which_objects_can_be_wrapped(Factory $factory, Definition $definition)
    {
        $this->beConstructedWith($factory, [$definition]);

        $definition->describeProxyFor(Argument::type('\DateTime'))->willReturn(true);

        $this->canWrap(new \DateTime())->shouldReturn(true);
    }

    function it_wrap_objects(Factory $factory, Definition $definition)
    {
        $this->beConstructedWith($factory, [$definition]);

        $definition->describeProxyFor(Argument::type('\DateTime'))->willReturn(true);
        $factory->createProxy(Argument::type('\DateTime'), $definition)->shouldBeCalled();

        $this->wrap(new \DateTime);
    }
}
