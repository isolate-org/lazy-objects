<?php

namespace Isolate\LazyObjects\Tests\Double\EntityFake;

use Isolate\LazyObjects\Proxy\Method\Replacement;
use Isolate\LazyObjects\WrappedObject;

class GetItemsReplacement implements Replacement
{
    /**
     * @var
     */
    private $returnValue;

    /**
     * @param $returnValue
     */
    public function __construct($returnValue)
    {
        $this->returnValue = $returnValue;
    }

    /**
     * Result of this method is going to be returned as a replacement
     * for a result of $methodName executed on $object.
     *
     * @param WrappedObject $proxy
     * @param string $methodName
     * @param array $params
     * @return mixed
     */
    public function execute(WrappedObject $proxy, $methodName, array $params = [])
    {
        return $this->returnValue;
    }
}
