<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use ProxyManager\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;

class HasMethodReplacement extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(PropertyGenerator $methodReplacementsProperty)
    {
        parent::__construct('hasMethodReplacement');
        $methodReplacementsProperty  = $methodReplacementsProperty->getName();
        $this->setVisibility(self::VISIBILITY_PRIVATE);

        $this->setParameter(new ParameterGenerator('methodName'));

        $body =
              "foreach (\$this->$methodReplacementsProperty as \$replacementDefinition) {\n"
            . "    if (\$replacementDefinition->getMethod()->isEqualTo(\$methodName)) {\n"
            . "         return true;\n"
            . "    }\n"
            . "}\n\n"
            . "return false;\n";

        $this->setBody($body);
    }
}
