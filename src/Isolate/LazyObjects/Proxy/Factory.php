<?php

namespace Isolate\LazyObjects\Proxy;

/**
 * @api
 */
interface Factory
{
    /**
     * @param mixed $object
     * @param Definition $definition
     * @return mixed $object
     * 
     * @api
     */
    public function createProxy($object, Definition $definition);
}
