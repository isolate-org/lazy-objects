<?php

namespace Isolate\LazyObjects\Proxy\Method;

interface Replacement
{
    /**
     * Result of this method is going to be returned as a replacement
     * for a result of $methodName executed on $object.
     *
     * @param mixed $object
     * @param string $methodName
     * @param array $params
     * @return mixed
     */
    public function execute($object, $methodName, array $params = []);
}
