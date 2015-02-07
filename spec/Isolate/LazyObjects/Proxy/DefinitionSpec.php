<?php

namespace spec\Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\ClassName;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefinitionSpec extends ObjectBehavior
{
    function it_throws_exception_when_one_of_properties_array_value_is_not_lazy_property()
    {
        $this->shouldThrow(new InvalidArgumentException("Proxy definitions require all properties to be an instance of Isolate\\LazyObjects\\Proxy\\LazyProperty"))
            ->during("__construct", [new ClassName("DateTime"), [new \DateTime()]]);
    }

    function it_describe_proxy_for_objects_that_are_instance_of_used_class()
    {
        $this->beConstructedWith(new ClassName('DateTime'));

        $this->describeProxyFor(new \DateTime())->shouldReturn(true);
        $this->describeProxyFor(new \ArrayObject())->shouldReturn(false);
    }
}
