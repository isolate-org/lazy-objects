<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

class GetWrappedObject extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(PropertyGenerator $methodReplacementsProperty)
    {
        parent::__construct('getWrappedObject');
        $methodReplacementsProperty  = $methodReplacementsProperty->getName();
        $this->setDocblock('{@inheritDoc}');

        $this->setBody("return \$this->" . $methodReplacementsProperty . ";");
    }
}
