<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;

class ClassName
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param string $className
     * @throws InvalidArgumentException
     */
    public function __construct($className)
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf("Class \"%s\" does not exists.", $className));
        }

        $this->className = $className;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->className;
    }

    /**
     * @param $object
     * @return bool
     */
    public function itFits($object)
    {
        return is_a($object, $this->className);
    }
}
