<?php

namespace spec\Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Method;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MethodsSpec extends ObjectBehavior
{
    function it_is_instance_of_array_object()
    {
        $this->shouldBeAnInstanceOf('ArrayObject');
    }

    function it_throws_exception_when_constructed_from_non_methods()
    {
        $this->shouldThrow(new InvalidArgumentException("Methods collection require method instances in constructor."))
            ->during("__construct", [["string"]]);
    }

    function it_does_not_have_method_by_default()
    {
        $this->has('method')->shouldReturn(false);
    }

    function it_has_methods_that_was_used_to_create_collection(Method\Replacement $replacement)
    {
        $this->beConstructedWith([new Method(new Method\Name("foo"), $replacement->getWrappedObject())]);
        $this->has("foo")->shouldReturn(true);
    }
}
