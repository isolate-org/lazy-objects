<?php

namespace Coduo\LazyObjects\Proxy\Method;

interface Replacement
{
    /**
     * @param array $parameters array of parameters passed to method. Might be empty
     * @param mixed $object that method we are replacing
     * @return mixed
     */
    public function call(array $parameters, $object);
}
