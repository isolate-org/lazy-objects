<?php

namespace Isolate\LazyObjects\Tests\Double;

class EntityFake
{
    private $items;

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

    public function removeItem($item)
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }
    }

    public function __sleep()
    {
        return ['items'];
    }
}
