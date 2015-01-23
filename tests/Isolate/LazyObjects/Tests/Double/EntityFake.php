<?php

namespace Isolate\LazyObjects\Tests\Double;

class EntityFake
{
    protected $items = [];

    public function getItems()
    {
        return $this->items;
    }
}
