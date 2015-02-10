<?php

namespace Isolate\LazyObjects;

use Isolate\LazyObjects\Proxy\LazyProperty;

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
}
