<?php

namespace Isolate\LazyObjects\Proxy\Property;

/**
 * @api
 */
interface ValueInitializer
{
    /**
     * Do not modify property value here, it is done automatically by Isolate\LazyObjects\Object\Property\Initializer
     * Value that will be set to the property is the result of this method.
     * Any value set to the property inside of this method will be overwritten with method result.
     * 
     * @param mixed $object that property holds the property.
     * @param mixed $defaultPropertyValue default of property that was set before initialization
     * @return mixed
     * 
     * @api
     */
    public function initialize($object, $defaultPropertyValue);
}
