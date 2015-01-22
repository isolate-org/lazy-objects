<?php

namespace Coduo\LazyObjects;

use Coduo\LazyObjects\Proxy\Definition;
use Coduo\LazyObjects\Proxy\Factory;
use Coduo\LazyObjects\Exception\RuntimeException;

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
     */
    public function __construct(Factory $factory, array $definitions = [])
    {
        $this->definitions = $definitions;
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
     * @return mixed
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
