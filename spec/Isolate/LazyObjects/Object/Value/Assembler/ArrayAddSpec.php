<?php

namespace spec\Isolate\LazyObjects\Object\Value\Assembler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayAddSpec extends ObjectBehavior
{
    function it_is_a_assembler()
    {
        $this->shouldImplement('Isolate\LazyObjects\Object\Value\Assembler');
    }

    function it_adds_values_from_old_array_to_new_array()
    {
        $this->assemble(["foz", "baz"], ["foo", "bar"])->shouldReturn(
            ["foz", "baz", "foo", "bar"]
        );
    }
}
