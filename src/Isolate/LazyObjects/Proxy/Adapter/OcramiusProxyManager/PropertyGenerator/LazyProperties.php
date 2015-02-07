<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\PropertyGenerator;

use ProxyManager\Generator\Util\UniqueIdentifierGenerator;
use Zend\Code\Generator\PropertyGenerator;

class LazyProperties extends PropertyGenerator
{
    public function __construct()
    {
        parent::__construct(UniqueIdentifierGenerator::getIdentifier('lazyProperties'));

        $this->setDefaultValue(array());
        $this->setVisibility(self::VISIBILITY_PRIVATE);
    }
}
