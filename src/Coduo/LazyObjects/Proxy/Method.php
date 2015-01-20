<?php

namespace Coduo\LazyObjects\Proxy;

use Coduo\LazyObjects\Exception\InvalidArgumentException;
use Coduo\LazyObjects\Proxy\Method\Replacement;

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
     * @param $name
     * @param Replacement $replacement
     * @throws InvalidArgumentException
     */
    public function __construct($name, Replacement $replacement)
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Method name can't be empty.");
        }

        $this->name = $name;
        $this->replacement = $replacement;
    }

    /**
     * @return Replacement
     */
    public function getReplacement()
    {
        return $this->replacement;
    }

    /**
     * @return string
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
        return $this->name === $name;
    }
}
