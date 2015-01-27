<?php

namespace Isolate\LazyObjects\Proxy\Method;

use Isolate\LazyObjects\Exception\InvalidArgumentException;

class Name
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param $name
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
}
