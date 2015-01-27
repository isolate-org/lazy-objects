<?php

namespace Isolate\LazyObjects\Object;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Exception\NotExistingPropertyException;
use Isolate\LazyObjects\Object\Value\Assembler;
use Isolate\LazyObjects\Object\Value\AssemblerFactory;

class PropertyValueSetter
{
    /**
     * @var Assembler
     */
    private $assemblerFactory;

    /**
     * @param AssemblerFactory $assemblerFactory
     */
    public function __construct(AssemblerFactory $assemblerFactory)
    {
        $this->assemblerFactory = $assemblerFactory;
    }

    /**
     * @param $object
     * @param $propertyName
     * @param $value
     * @throws InvalidArgumentException
     * @throws NotExistingPropertyException
     */
    public function set($object, $propertyName, $value)
    {
        $this->validateObject($object);
        $reflection = new \ReflectionClass($object);
        $this->validateProperty($reflection, $object, $propertyName);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        $oldValue = $property->getValue($object);
        $assembler = $this->assemblerFactory->createFor($value, $oldValue);
        $property->setValue($object, $assembler->assemble($value, $oldValue));
    }

    /**
     * @param $value
     * @throws InvalidArgumentException
     */
    private function validateObject($value)
    {
        if (!is_object($value)) {
            throw new InvalidArgumentException(sprintf(
                "PropertyAccessor require object to access property, \"%s\" passed.",
                gettype($value)
            ));
        }
    }

    /**
     * @param \ReflectionClass $reflection
     * @param $object
     * @param $propertyName
     * @throws NotExistingPropertyException
     */
    private function validateProperty(\ReflectionClass $reflection, $object, $propertyName)
    {
        if (!$reflection->hasProperty($propertyName)) {
            throw new NotExistingPropertyException(sprintf(
                "Property \"%s\" does not exists in \"%s\" class.",
                $propertyName,
                get_class($object)
            ));
        }
    }
}
