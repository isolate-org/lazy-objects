<?php

namespace Isolate\LazyObjects;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\Factory;
use Isolate\LazyObjects\Exception\RuntimeException;

class Wrapper
{
    /**
     * @var array|Definition[]
     */
    private $definitions;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param Factory $factory
     * @param array $definitions
     * @throws InvalidArgumentException
     */
    public function __construct(Factory $factory, $definitions = [])
    {
        $this->definitions = [];

        if (!is_array($definitions) && !$definitions instanceof \Traversable) {
            throw new InvalidArgumentException("Lazy objects definitions collection must be traversable.");
        }

        foreach ($definitions as $definition) {
            if (!$definition instanceof Definition) {
                throw new InvalidArgumentException("Lazy object definition must be an instance of Isolate\\LazyObjects\\Proxy\\Definition");
            }

            $this->definitions[] = $definition;
        }

        $this->factory = $factory;
    }

    /**
     * @param $object
     * @return bool
     */
    public function canWrap($object)
    {
        foreach ($this->definitions as $proxyDefinition) {
            if ($proxyDefinition->describeProxyFor($object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $object
     * @return WrappedObject
     * @throws RuntimeException
     */
    public function wrap($object)
    {
        foreach ($this->definitions as $proxyDefinition) {
            if ($proxyDefinition->describeProxyFor($object)) {
                return $this->factory->createProxy($object, $proxyDefinition);
            }
        }

        throw new RuntimeException("Can\"t wrap objects that does\"t fit any proxy definition.");
    }
}
