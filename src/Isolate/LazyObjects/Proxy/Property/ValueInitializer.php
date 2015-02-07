<?php

namespace Isolate\LazyObjects\Proxy\Property;

interface ValueInitializer
{
    public function initialize($defaultPropertyValue);
}
