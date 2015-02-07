<?php

namespace Isolate\LazyObjects\Tests\Double\EntityFake;

use Isolate\LazyObjects\Proxy\Property\ValueInitializer;

class ItemsValueInitilizer implements ValueInitializer
{
    /**
     * @var
     */
    private $items;

    /**
     * @param $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    public function initialize($defaultPropertyValue)
    {
        return $this->items;
    }
}
