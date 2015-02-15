<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use ProxyManager\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;

class GetMethodReplacement extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(PropertyGenerator $methodReplacementsProperty)
    {
        parent::__construct('getMethodReplacement');
        $methodReplacementsProperty  = $methodReplacementsProperty->getName();
        $this->setVisibility(self::VISIBILITY_PRIVATE);

        $this->setParameter(new ParameterGenerator('methodName'));

        $body =
              "foreach (\$this->$methodReplacementsProperty as \$replacementDefinition) {\n"
            . "    if (\$replacementDefinition->getMethod()->isEqualTo(\$methodName)) {\n"
            . "         return \$replacementDefinition;\n"
            . "    }\n"
            . "}\n\n"
            . "throw new \\RuntimeException(sprintf(\"Method replacement for %s does not exists.\", \$methodName));\n";

        $this->setBody($body);
    }
}
