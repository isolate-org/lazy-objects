<?php

namespace spec\Isolate\LazyObjects\Proxy\Method;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameSpec extends ObjectBehavior
{
    function it_throws_exception_when_created_with_non_string()
    {
        $this->shouldThrow(new InvalidArgumentException("Method name must be a valid string."))
            ->during('__construct', [new \DateTime()]);
    }

    function it_throws_exception_when_created_with_empty_method_name()
    {
        $this->shouldThrow(new InvalidArgumentException("Method name can not be empty."))
            ->during('__construct', [""]);
    }
}
