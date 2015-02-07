<?php

namespace spec\Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MethodSpec extends ObjectBehavior
{
    function it_throws_exception_when_method_name_is_not_string()
    {
        $this->shouldThrow(new InvalidArgumentException("Method name must be a valid string."))
            ->during("__construct", [new \DateTime()]);
    }

    function it_throws_exception_when_method_name_is_empty_string()
    {
        $this->shouldThrow(new InvalidArgumentException("Method name can not be empty."))
            ->during("__construct", [""]);
    }

    function it_is_equal_to_method_with_uppercase_characters()
    {
        $this->beConstructedWith("method");
        $this->isEqualTo("METHOD")->shouldReturn(true);
    }
}
