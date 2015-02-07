<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;

class Method
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function __construct($name)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException("Method name must be a valid string.");
        }

        if (empty($name)) {
            throw new InvalidArgumentException("Method name can not be empty.");
        }

        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param $method
     * @return bool
     */
    public function isEqualTo($method)
    {
        return strtolower($this->name) === strtolower($method);
    }
}
