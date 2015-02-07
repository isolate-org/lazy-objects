<?php

namespace Isolate\LazyObjects\Tests\Double\EntityFake;

use Isolate\LazyObjects\Proxy\Property\ValueInitializer;

class IncrementationInitializerStub implements ValueInitializer
{
    /**
     * @var
     */
    private $items;

    /**
     * @param $items
     */
    public function __construct()
    {
        $this->items = 0;
    }

    public function initialize($object, $defaultPropertyValue)
    {
        $this->items += 1;
        return $this->items;
    }
}
