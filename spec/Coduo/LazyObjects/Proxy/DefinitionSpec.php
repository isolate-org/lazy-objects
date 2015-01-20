<?php

namespace spec\Coduo\LazyObjects\Proxy;

use Coduo\LazyObjects\Proxy\ClassName;
use Coduo\LazyObjects\Proxy\Methods;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefinitionSpec extends ObjectBehavior
{
    function it_fits_only_for_instances_of_specific_classes()
    {
        $object = new \DateTime();
        $this->beConstructedWith(new ClassName(get_class($object)), new Methods());
        $this->describeProxyFor($object)->shouldReturn(true);
    }
}
