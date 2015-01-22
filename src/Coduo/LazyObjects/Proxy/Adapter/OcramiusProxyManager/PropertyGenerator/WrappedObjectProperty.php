<?php

namespace Coduo\LazyObjects\Proxy\Adapter\OcramiusProxyManager\PropertyGenerator;

use ProxyManager\Generator\Util\UniqueIdentifierGenerator;
use Zend\Code\Generator\PropertyGenerator;

class WrappedObjectProperty extends PropertyGenerator
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(UniqueIdentifierGenerator::getIdentifier('wrappedObject'));

        $this->setVisibility(self::VISIBILITY_PRIVATE);
        $this->setDocblock('@var \\Closure|null initializer responsible for generating the wrapped object');
    }
}
