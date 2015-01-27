<?php

namespace Isolate\LazyObjects\Object\Value;

use Isolate\LazyObjects\Object\Value\Assembler;

class AssemblerFactory
{
    /**
     * @param mixed $firstValue
     * @param mixed $secondValue
     * @return Assembler
     */
    public function createFor($firstValue, $secondValue)
    {
        if (is_array($firstValue) && is_array($secondValue)) {
            return new Assembler\ArrayAdd();
        }

        if (is_array($firstValue) && $this->isTraversableArrayObject($secondValue)
            || $this->isTraversableArrayObject($firstValue) && is_array($secondValue)) {
            return new Assembler\ArrayAdd();
        }

        if ($this->isTraversableArrayObject($firstValue) && $this->isTraversableArrayObject($secondValue)) {
            return new Assembler\ArrayAdd();
        }

        return new Assembler\Replace();
    }

    private function isTraversableArrayObject($value)
    {
        if (!is_object($value)) {
            return false;
        }

        return $value instanceof \Traversable && $value instanceof \ArrayAccess;
    }
}
