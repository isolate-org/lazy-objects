<?php

namespace spec\Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Method\Replacement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MethodSpec extends ObjectBehavior
{
    function it_throws_exception_when_method_name_is_empty(Replacement $replacement)
    {
        $this->shouldThrow(new InvalidArgumentException("Method name can't be empty."))
            ->during('__construct', ['', $replacement]);
    }
}
