<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\ProxyGenerator\LazyObjectsProxyGenerator;
use Isolate\LazyObjects\Proxy\LazyProperty;
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
     * @return \Isolate\LazyObjects\WrappedObject
     * @throws InvalidArgumentException
     */
    public function createProxy($instance, array $lazyProperties = array())
    {
        foreach ($lazyProperties as $initializer) {
            if (!$initializer instanceof LazyProperty) {
                throw new InvalidArgumentException("Lazy property needs to be an instance of Isolate\\LazyObjects\\Proxy\\Property");
            }
        }

        $proxyClassName = $this->generateProxy(get_class($instance));

        return new $proxyClassName($instance, $lazyProperties);
    }

    /**
     * {@inheritDoc}
     */
    protected function getGenerator()
    {
        return $this->generator ?: $this->generator = new LazyObjectsProxyGenerator();
    }
}

