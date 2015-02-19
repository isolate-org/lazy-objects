<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\LazyObjects\Proxy\Property\ValueInitializer;

class LazyProperty
{
    /**
     * @var Name
     */
    private $name;

    /**
     * @var ValueInitializer
     */
    private $valueInitializer;

    /**
     * @var array|Method[] $triggers
     */
    private $triggers;

    /**
     * @var \Closure
     */
    private $initializationCallback;

    /**
     * @param Name $name
     * @param ValueInitializer $valueInitializer
     * @param array|Method[] $triggers
     * @throws InvalidArgumentException
     */
    public function __construct(Name $name, ValueInitializer $valueInitializer, $triggers = [])
    {
        foreach ($triggers as $trigger) {
            if (!$trigger instanceof Method) {
                throw new InvalidArgumentException("Each trigger must be an instance of Isolate\\LazyObjects\\Proxy\\Method");
            }
        }

        $this->name = $name;
        $this->valueInitializer = $valueInitializer;
        $this->triggers = $triggers;
    }

    /**
     * @return ValueInitializer
     */
    public function getValueInitializer()
    {
        return $this->valueInitializer;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function hasTriggers()
    {
        return (boolean) count($this->triggers);
    }

    /**
     * @param $methodName
     * @return bool
     */
    public function isTriggeredBy($methodName)
    {
        foreach ($this->triggers as $trigger) {
            if ($trigger->isEqualTo($methodName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Closure $initializationCallback
     */
    public function setInitializationCallback(\Closure $initializationCallback)
    {
        $this->initializationCallback = $initializationCallback;
    }

    /**
     * @return bool
     */
    public function hasInitializationCallback()
    {
        return !is_null($this->initializationCallback);
    }

    /**
     * @return \Closure
     */
    public function getInitializationCallback()
    {
        return $this->initializationCallback;
    }
}
