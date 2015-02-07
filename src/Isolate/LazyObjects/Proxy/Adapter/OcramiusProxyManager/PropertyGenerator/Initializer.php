<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\PropertyGenerator;

use ProxyManager\Generator\Util\UniqueIdentifierGenerator;
use Zend\Code\Generator\PropertyGenerator;

class Initializer extends PropertyGenerator
{
    public function __construct()
    {
        parent::__construct(UniqueIdentifierGenerator::getIdentifier('initializer'));

        $this->setDefaultValue([]);
        $this->setVisibility(self::VISIBILITY_PRIVATE);
    }
}
