<?php

namespace Isolate\LazyObjects\Proxy\LazyProperty;

/**
 * @api
 */
interface InitializationCallback 
{
    /**
     * @param mixed $defaultValue
     * @param mixed $newValue
     * @param mixed $targetObject
     * 
     * @api
     */
    public function execute($defaultValue, $newValue, $targetObject);
}
