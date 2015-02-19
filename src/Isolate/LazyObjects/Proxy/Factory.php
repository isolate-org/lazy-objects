<?php

namespace Isolate\LazyObjects\Proxy;

interface Factory
{
    /**
     * @param mixed $object
     * @param Definition $definition
     * @return mixed $object
     */
    public function createProxy($object, Definition $definition);
}
