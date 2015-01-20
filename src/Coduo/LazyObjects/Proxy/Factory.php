<?php

namespace Coduo\LazyObjects\Proxy;

interface Factory
{
    /**
     * @param $object
     * @param Definition $definition
     * @return $object
     */
    public function createProxy($object, Definition $definition);
}
