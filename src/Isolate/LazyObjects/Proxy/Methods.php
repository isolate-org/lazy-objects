<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;

class Methods extends \ArrayObject
{
    public function __construct(array $methods = [])
    {
        foreach ($methods as $method) {
            if (!$method instanceof Method) {
                throw new InvalidArgumentException("Methods collection require method instances in constructor.");
            }
        }

        parent::__construct($methods);
    }

    public function has($name)
    {
        foreach ($this->getIterator() as $method) {
            if ($method->hasName($name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array|Method[]
     */
    public function all()
    {
        return $this->getIterator();
    }

    /**
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $this->append($method);
    }
}
