<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager;

use Isolate\LazyObjects\Object\PropertyAccessor;
use Isolate\LazyObjects\Object\Value\AssemblerFactory;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory\LazyObjectsFactory;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\Factory as BaseFactory;

class Factory implements BaseFactory
{
    /**
     * @var LazyObjectsFactory
     */
    private $lazyObjectsFactory;

    /**
     * @param LazyObjectsFactory $lazyObjectFactory
     */
    public function __construct(LazyObjectsFactory $lazyObjectFactory)
    {
        $this->lazyObjectsFactory = $lazyObjectFactory;
    }

    /**
     * @param $object
     * @param Definition $definition
     * @return \Isolate\LazyObjects\WrappedObject $object
     */
    public function createProxy($object, Definition $definition)
    {
        return $this->lazyObjectsFactory->createProxy(
            $object,
            $definition->getLazyProperties(),
            $definition->getMethodReplacements()
        );
    }
}
