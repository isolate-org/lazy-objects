<?php

namespace Coduo\LazyObjects\Proxy\Adapter\OcramiusProxyManager;

use Coduo\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory\LazyObjectsFactory;
use Coduo\LazyObjects\Proxy\Definition;
use Coduo\LazyObjects\Proxy\Factory as BaseFactory;

class Factory implements BaseFactory
{
    private $lazyObjectsFactory;

    public function __construct()
    {
        $this->lazyObjectsFactory = new LazyObjectsFactory();
    }

    /**
     * @param $object
     * @param Definition $definition
     * @return $object
     */
    public function createProxy($object, Definition $definition)
    {
        $proxyMethods = [];
        foreach ($definition->getMethods()->all() as $methodDefinition) {

            $proxyMethods[$methodDefinition->getName()] = function ($proxy, $instance, $method, $params, & $returnEarly) use ($methodDefinition) {
                $returnEarly = true;
                return $methodDefinition->getReplacement()->call($params, $instance);
            };
        }

        return $this->lazyObjectsFactory->createProxy(
            $object,
            $proxyMethods
        );
    }
}
