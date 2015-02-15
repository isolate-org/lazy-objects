<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

class GetMethodReplacements extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(PropertyGenerator $methodReplacementsProperty)
    {
        parent::__construct('getMethodReplacements');
        $methodReplacementsProperty  = $methodReplacementsProperty->getName();
        $this->setDocblock('{@inheritDoc}');

        $this->setBody("return \$this->" . $methodReplacementsProperty . ";");
    }
}
