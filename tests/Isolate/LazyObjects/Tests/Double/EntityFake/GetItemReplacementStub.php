<?php

namespace Isolate\LazyObjects\Tests\Double\EntityFake;

use Isolate\LazyObjects\Proxy\Method\Replacement;

class GetItemReplacementStub implements Replacement
{
    /**
     * @var
     */
    private $getItemResult;

    /**
     * @param $getItemResult
     */
    public function __construct($getItemResult)
    {
        $this->getItemResult = $getItemResult;
    }

    /**
     * @param array $parameters
     * @param mixed $object
     * @return mixed
     */
    public function call(array $parameters, $object)
    {
        return $this->getItemResult;
    }
}
