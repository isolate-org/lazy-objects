<?php

namespace Isolate\LazyObjects;

use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\MethodReplacement;

interface WrappedObject
{
    /**
     * @return mixed
     */
    public function getWrappedObject();

    /**
     * @return array|LazyProperty[]
     */
    public function getLazyProperties();

    /**
     * @return array|MethodReplacement[]
     */
    public function getMethodReplacements();
}
