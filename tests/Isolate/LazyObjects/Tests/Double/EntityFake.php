<?php

namespace Isolate\LazyObjects\Tests\Double;

class EntityFake
{
    protected $items;

    public function __construct($items = [])
    {
        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }
}
