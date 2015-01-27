<?php

namespace Isolate\LazyObjects\Object\Value;

interface Assembler
{
    /**
     * @param mixed $newValue
     * @param mixed $oldValue
     * @return mixed
     */
    public function assemble($newValue, $oldValue);
}
