<?php

namespace Isolate\LazyObjects\Proxy;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Method\Name;
use Isolate\LazyObjects\Proxy\Method\Replacement;

class Method
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Replacement
     */
    private $replacement;

    /**
     * @var string
     */
    private $targetPropertyName;

    /**
     * @param Name $name
     * @param Replacement $replacement
     * @param $targetPropertyName
     * @throws InvalidArgumentException
     */
    public function __construct(Name $name, Replacement $replacement, $targetPropertyName = null)
    {
        $this->name = $name;
        $this->replacement = $replacement;
        $this->targetPropertyName = $targetPropertyName;
    }

    /**
     * @return Replacement
     */
    public function getReplacement()
    {
        return $this->replacement;
    }

    /**
     * @return Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasName($name)
    {
        return (string) $this->name === $name;
    }

    /**
     * @return bool
     */
    public function hasDefinedTargetProperty()
    {
        return !is_null($this->targetPropertyName);
    }

    /**
     * @return mixed
     */
    public function getTargetPropertyName()
    {
        return $this->targetPropertyName;
    }
}
