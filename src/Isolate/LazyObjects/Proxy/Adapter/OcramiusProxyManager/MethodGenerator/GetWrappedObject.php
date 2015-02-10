<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

class GetWrappedObject extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(PropertyGenerator $lazyPropertiesProperty)
    {
        parent::__construct('getWrappedObject');
        $lazyPropertiesProperty  = $lazyPropertiesProperty->getName();
        $this->setDocblock('{@inheritDoc}');

        $this->setBody("return \$this->" . $lazyPropertiesProperty . ";");
    }
}
