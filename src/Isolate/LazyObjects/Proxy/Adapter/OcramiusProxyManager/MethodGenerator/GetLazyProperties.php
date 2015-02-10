<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

class GetLazyProperties extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(PropertyGenerator $lazyPropertiesProperty)
    {
        parent::__construct('getLazyProperties');
        $lazyPropertiesProperty  = $lazyPropertiesProperty->getName();
        $this->setDocblock('{@inheritDoc}');

        $this->setBody("return \$this->" . $lazyPropertiesProperty . ";");
    }
}
