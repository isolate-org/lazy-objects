<?php

namespace Isolate\LazyObjects\Proxy\LazyProperty;

interface InitializationCallback 
{
    /**
     * @param mixed $defaultValue
     * @param mixed $newValue
     * @param mixed $targetObject
     */
    public function execute($defaultValue, $newValue, $targetObject);
}
