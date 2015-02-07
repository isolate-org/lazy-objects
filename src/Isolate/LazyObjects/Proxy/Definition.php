<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;

class Definition
{
    /**
     * @var ClassName
     */
    private $className;

    /**
     * @var LazyProperty[]|array $lazyProperties
     */
    private $lazyProperties;

    /**
     * @param ClassName $className
     * @param LazyProperty[]|array $lazyProperties
     * @throws InvalidArgumentException
     */
    public function __construct(ClassName $className, array $lazyProperties = [])
    {
        foreach ($lazyProperties as $property) {
            if (!$property instanceof LazyProperty) {
                throw new InvalidArgumentException("Proxy definitions require all properties to be an instance of Isolate\\LazyObjects\\Proxy\\LazyProperty");
            }
        }

        $this->className = $className;
        $this->lazyProperties = $lazyProperties;
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
     * @return array|LazyProperty[]
     */
    public function getLazyProperties()
    {
        return $this->lazyProperties;
    }
}
