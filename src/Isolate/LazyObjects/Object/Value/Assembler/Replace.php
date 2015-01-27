<?php

namespace Isolate\LazyObjects\Object\Value\Assembler;

use Isolate\LazyObjects\Object\Value\Assembler as BaseAssembler;

class Replace implements BaseAssembler
{
    /**
     * @param mixed $newValue
     * @param mixed $oldValue
     * @return mixed
     */
    public function assemble($newValue, $oldValue)
    {
        return $newValue;
    }
}
