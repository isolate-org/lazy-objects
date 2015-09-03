<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\LazyProperty\InitializationCallback;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\LazyObjects\Proxy\Property\ValueInitializer;

/**
 * @api
 */
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
     * 
     * @api
     */
    public function getValueInitializer()
    {
        return $this->valueInitializer;
    }

    /**
     * @return string
     * 
     * @api
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     * 
     * @api
     */
    public function hasTriggers()
    {
        return (boolean) count($this->triggers);
    }

    /**
     * @param $methodName
     * @return bool
     * 
     * @api
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
     * @param InitializationCallback $initializationCallback
     * 
     * @api
     */
    public function setInitializationCallback(InitializationCallback $initializationCallback)
    {
        $this->initializationCallback = $initializationCallback;
    }

    /**
     * @return bool
     * 
     * @api
     */
    public function hasInitializationCallback()
    {
        return !is_null($this->initializationCallback);
    }

    /**
     * @return InitializationCallback
     * 
     * @api
     */
    public function getInitializationCallback()
    {
        return $this->initializationCallback;
    }
}
