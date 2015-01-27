<?php

namespace spec\Isolate\LazyObjects\Object\Value\Assembler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReplaceSpec extends ObjectBehavior
{
    function it_is_a_assembler()
    {
        $this->shouldImplement('Isolate\LazyObjects\Object\Value\Assembler');
    }

    function it_always_return_new_value_ignoring_the_old_one()
    {
        $this->assemble("New Value", "Old Value")->shouldReturn("New Value");
    }
}
