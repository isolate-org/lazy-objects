<?php

namespace spec\Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassNameSpec extends ObjectBehavior
{
    function it_throws_exception_when_class_does_not_exists()
    {
        $this->shouldThrow(new InvalidArgumentException("Class \"InvalidClassName\" does not exists."))
            ->during('__construct', ['InvalidClassName']);
    }

    function it_can_be_converted_to_string()
    {
        $this->beConstructedWith("\\DateTime");
        $this->__toString()->shouldReturn("\\DateTime");
    }

    function it_knows_if_object_is_an_instance_of_it()
    {
        $this->beConstructedWith("\\DateTime");
        $this->itFits(new \DateTime())->shouldReturn(true);
    }
}
