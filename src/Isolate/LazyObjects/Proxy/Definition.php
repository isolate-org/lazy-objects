<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;

/**
 * @api
 */
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
     * @var array
     */
    private $methodReplacements;

    /**
     * @param ClassName $className
     * @param array|LazyProperty[] $lazyProperties
     * @param array|MethodReplacement[] $methodReplacements
     * @throws InvalidArgumentException
     */
    public function __construct(ClassName $className, array $lazyProperties = [], array $methodReplacements = [])
    {
        $this->validateLazyPropertiesDefinitions($lazyProperties);
        $this->validateMethodReplacementsDefinitions($methodReplacements);

        $this->className = $className;
        $this->lazyProperties = $lazyProperties;
        $this->methodReplacements = $methodReplacements;
    }

    /**
     * @return ClassName
     * 
     * @api
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param $object
     * @return bool
     * 
     * @api
     */
    public function describeProxyFor($object)
    {
        return $this->className->itFits($object);
    }

    /**
     * @return array|LazyProperty[]
     * 
     * @api
     */
    public function getLazyProperties()
    {
        return $this->lazyProperties;
    }

    /**
     * @return array
     * 
     * @api
     */
    public function getMethodReplacements()
    {
        return $this->methodReplacements;
    }

    /**
     * @param array $lazyProperties
     * @throws InvalidArgumentException
     */
    private function validateLazyPropertiesDefinitions(array $lazyProperties)
    {
        foreach ($lazyProperties as $property) {
            if (!$property instanceof LazyProperty) {
                throw new InvalidArgumentException("Proxy definitions require all properties to be an instance of Isolate\\LazyObjects\\Proxy\\LazyProperty");
            }
        }
    }

    /**
     * @param array $methodReplacements
     * @throws InvalidArgumentException
     */
    private function validateMethodReplacementsDefinitions(array $methodReplacements)
    {
        foreach ($methodReplacements as $methodReplacement) {
            if (!$methodReplacement instanceof MethodReplacement) {
                throw new InvalidArgumentException("Proxy definitions require all method replacements to be an instance of Isolate\\LazyObjects\\Proxy\\MethodReplacement");
            }
        }
    }
}
