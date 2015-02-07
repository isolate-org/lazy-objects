<?php

namespace spec\Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClassNameSpec extends ObjectBehavior
{
    function it_throws_exception_when_class_name_does_not_exists()
    {
        $this->shouldThrow(new InvalidArgumentException("Class \"InvalidClassName\" does not exists."))
            ->during("__construct", ["InvalidClassName"]);
    }
}
