<?php

namespace Coduo\LazyObjects\Proxy\Adapter\OcramiusProxyManager;

use Coduo\LazyObjects\Proxy\Definition;
use Coduo\LazyObjects\Proxy\Factory as BaseFactory;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;

class Factory implements BaseFactory
{
    private $accessInterceptorFactory;

    public function __construct()
    {
        $this->accessInterceptorFactory = new AccessInterceptorValueHolderFactory();
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

        return $this->accessInterceptorFactory->createProxy(
            $object,
            $proxyMethods
        );
    }
}
