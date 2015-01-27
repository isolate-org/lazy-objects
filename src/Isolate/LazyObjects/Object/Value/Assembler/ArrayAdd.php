<?php

namespace Isolate\LazyObjects\Object\Value\Assembler;

use Isolate\LazyObjects\Object\Value\Assembler;

class ArrayAdd implements Assembler
{
    /**
     * @param mixed $newValue
     * @param mixed $oldValue
     * @return mixed
     */
    public function assemble($newValue, $oldValue)
    {
        foreach ($oldValue as $oldValueElement) {
            $newValue[] = $oldValueElement;
        }

        return $newValue;
    }
}
