<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager;

use Isolate\LazyObjects\Object\PropertyValueSetter;
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
     * @var PropertyValueSetter
     */
    private $propertyValueSetter;

    /**
     * @param PropertyValueSetter $propertyValueSetter
     */
    public function __construct(PropertyValueSetter $propertyValueSetter)
    {
        $this->lazyObjectsFactory = new LazyObjectsFactory();
        $this->propertyValueSetter = $propertyValueSetter;
    }

    /**
     * @param $object
     * @param Definition $definition
     * @return \Isolate\LazyObjects\WrappedObject $object
     */
    public function createProxy($object, Definition $definition)
    {
        $proxyMethods = [];
        foreach ($definition->getMethods()->all() as $methodDefinition) {

            $proxyMethods[(string) $methodDefinition->getName()] = function ($proxy, $instance, $method, $params, & $returnEarly) use ($methodDefinition) {
                $replacementResult = $methodDefinition->getReplacement()->call($params, $instance);

                if ($methodDefinition->hasDefinedTargetProperty()) {
                    $this->propertyValueSetter->set($instance, $methodDefinition->getTargetPropertyName(), $replacementResult);
                } else {
                    $returnEarly = true;
                }

                return $replacementResult;
            };
        }

        return $this->lazyObjectsFactory->createProxy(
            $object,
            $proxyMethods
        );
    }
}
