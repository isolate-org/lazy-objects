<?php

namespace Isolate\LazyObjects\Tests\Double\EntityFake;

use Isolate\LazyObjects\Proxy\LazyProperty\InitializationCallback;

final class InitializationCallbackMock implements InitializationCallback
{
    private $executed;

    public function __construct()
    {
        $this->executed = false;
    }

    /**
     * @param mixed $defaultValue
     * @param mixed $newValue
     * @param mixed $targetObject
     */
    public function execute($defaultValue, $newValue, $targetObject)
    {
        $this->executed = true;
    }

    public function wasExecuted()
    {
        return $this->executed;
    }
}
