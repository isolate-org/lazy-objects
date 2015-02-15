<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\ProxyGenerator\LazyObjectsProxyGenerator;
use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\MethodReplacement;
use ProxyManager\Factory\AbstractBaseFactory;

/**
 * Factory responsible of producing proxy lazy objects
 */
class LazyObjectsFactory extends AbstractBaseFactory
{
    /**
     * @var LazyObjectsProxyGenerator|null
     */
    private $generator;

    /**
     * @param $instance
     * @param array $lazyProperties
     * @param array $methodReplacements
     * @return \Isolate\LazyObjects\WrappedObject
     * @throws InvalidArgumentException
     */
    public function createProxy($instance, array $lazyProperties = array(), array $methodReplacements = [])
    {
        foreach ($lazyProperties as $lazyProperty) {
            if (!$lazyProperty instanceof LazyProperty) {
                throw new InvalidArgumentException("Lazy property needs to be an instance of Isolate\\LazyObjects\\Proxy\\LazyProperty");
            }
        }

        foreach ($methodReplacements as $methodReplacement) {
            if (!$methodReplacement instanceof MethodReplacement) {
                throw new InvalidArgumentException("Method replacement needs to be an instance of Isolate\\LazyObjects\\Proxy\\MethodReplacement");
            }
        }

        $proxyClassName = $this->generateProxy(get_class($instance));

        return new $proxyClassName($instance, $lazyProperties, $methodReplacements);
    }

    /**
     * {@inheritDoc}
     */
    protected function getGenerator()
    {
        return $this->generator ?: $this->generator = new LazyObjectsProxyGenerator();
    }
}

