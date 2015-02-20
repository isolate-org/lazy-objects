<?php

namespace Isolate\LazyObjects\Proxy\Method;

use Isolate\LazyObjects\WrappedObject;

interface Replacement
{
    /**
     * Result of this method is going to be returned as a replacement
     * for a result of $methodName executed on $object.
     *
     * @param WrappedObject $proxy
     * @param string $methodName
     * @param array $params
     * @return mixed
     */
    public function execute(WrappedObject $proxy, $methodName, array $params = []);
}
