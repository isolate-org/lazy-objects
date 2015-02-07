<?php

namespace Isolate\LazyObjects\Object\Property;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Object\PropertyAccessor;
use Isolate\LazyObjects\Proxy\LazyProperty;

final class Initializer
{
    /**
     * @var array
     */
    private $initializedProperties;

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = new PropertyAccessor();
        $this->initializedProperties = [];
    }

    /**
     * @param array $lazyProperties
     * @param string $triggerMethod
     * @param $targetObject
     * @throws InvalidArgumentException
     */
    public function initialize($lazyProperties = [], $triggerMethod, $targetObject)
    {
        foreach ($lazyProperties as $property) {
            /* @var $property LazyProperty */
            $this->validateLazyProperty($property);

            if ($this->wasInitialized($property)) {
                continue ;
            }

            if (!$property->hasTriggers()) {
                $this->initializeProperty($property, $targetObject);
                continue ;
            }

            if ($property->isTriggeredBy($triggerMethod)) {
                $this->initializeProperty($property, $targetObject);
                continue ;
            }
        }
    }

    /**
     * @param $property
     * @throws InvalidArgumentException
     */
    private function validateLazyProperty($property)
    {
        if (!$property instanceof LazyProperty) {
            throw new InvalidArgumentException("Only lazy properties can be initialized");
        }
    }

    /**
     * @param LazyProperty $property
     * @return bool
     */
    private function wasInitialized(LazyProperty $property)
    {
        return in_array((string) $property->getName(), $this->initializedProperties, true);
    }

    /**
     * @param LazyProperty $property
     */
    private function markAsInitialized(LazyProperty $property)
    {
        $this->initializedProperties[] = (string) $property->getName();
    }

    /**
     * @param LazyProperty $property
     * @param $targetObject
     */
    private function initializeProperty(LazyProperty $property, $targetObject)
    {
        $defaultValue = $this->propertyAccessor->get($targetObject, (string)$property->getName());
        $propertyValue = $property->getValueInitializer()->initialize($defaultValue);
        $this->propertyAccessor->set($targetObject, (string)$property->getName(), $propertyValue);
        $this->markAsInitialized($property);
    }
}
