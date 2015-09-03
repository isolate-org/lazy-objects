<?php

namespace Isolate\LazyObjects;

use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\MethodReplacement;

/**
 * @api
 */
interface WrappedObject
{
    /**
     * @return mixed
     * 
     * @api
     */
    public function getWrappedObject();

    /**
     * @return array|LazyProperty[]
     * 
     * @api
     */
    public function getLazyProperties();

    /**
     * @return array|MethodReplacement[]
     * 
     * @api
     */
    public function getMethodReplacements();
}
