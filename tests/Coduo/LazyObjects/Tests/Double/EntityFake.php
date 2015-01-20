<?php

namespace Coduo\LazyObjects\Tests\Double;

class EntityFake
{
    protected $items = [];

    public function getItems()
    {
        return $this->items;
    }
}
