<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Code\Reflection\MethodReflection;

class MethodProxy extends MethodGenerator
{
    /**
     * @param \Zend\Code\Reflection\MethodReflection $originalMethod
     * @param \Zend\Code\Generator\PropertyGenerator $wrappedObjectProperty
     * @param \Zend\Code\Generator\PropertyGenerator $lazyPropertiesProperty
     * @param PropertyGenerator $initializerProperty
     * @return MethodProxy
     */
    public static function generateMethod(
        MethodReflection $originalMethod,
        PropertyGenerator $wrappedObjectProperty,
        PropertyGenerator $lazyPropertiesProperty,
        PropertyGenerator $initializerProperty
    ) {
        $method          = static::fromReflection($originalMethod);
        $forwardedParams = [];
        $params          = [];

        foreach ($originalMethod->getParameters() as $parameter) {
            $paramName = '$' . $parameter->name;
            $forwardedParams[] = $paramName;
            $params[] = var_export($parameter->name, true) . ' => ' . $paramName;
        }

        $paramsString = 'array(' . implode(', ', $params) . ')';

        $methodBody = '$this->' . $initializerProperty->getName() . "->initialize(\$this->" . $lazyPropertiesProperty->getName() . ", \"" . $method->getName() . "\", \$this->" . $wrappedObjectProperty->getName() . ");\n\n"
            . 'if ($this->hasMethodReplacement("' . $method->getName() . '")) {' . "\n"
            . '    return $this->getMethodReplacement("' . $method->getName() .'")->getReplacement()->execute($this, "' . $method->getName() . '", ' . $paramsString . ');' . "\n"
            . '}' . "\n\n"
            . '$result = $this->' . $wrappedObjectProperty->getName() . '->' . $method->getName() .  '(' . implode(', ', $forwardedParams) . ');' . "\n"
            . 'if ($result === $this->' . $wrappedObjectProperty->getName() . ') {' ."\n"
            . '    return $this;' . "\n"
            . "}\n\n"
            . 'return $result;';


        $method->setDocblock('{@inheritDoc}');
        $method->setBody(
            $methodBody
        );

        return $method;
    }
}
