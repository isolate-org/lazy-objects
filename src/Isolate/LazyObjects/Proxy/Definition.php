<?php

namespace Isolate\LazyObjects\Proxy;

class Definition
{
    /**
     * @var ClassName
     */
    private $className;

    /**
     * @var Methods
     */
    private $methods;

    /**
     * @param ClassName $className
     * @param Methods $methods
     */
    public function __construct(ClassName $className, Methods $methods)
    {
        $this->className = $className;
        $this->methods = $methods;
    }

    /**
     * @param $object
     * @return bool
     */
    public function describeProxyFor($object)
    {
        return $this->className->itFits($object);
    }

    /**
     * @return Methods
     */
    public function getMethods()
    {
        return $this->methods;
    }
}
