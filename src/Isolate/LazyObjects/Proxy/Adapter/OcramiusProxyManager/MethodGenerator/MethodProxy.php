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
        /* @var $method self */
        $method          = static::fromReflection($originalMethod);
        $forwardedParams = array();

        foreach ($originalMethod->getParameters() as $parameter) {
            $forwardedParams[]   = '$' . $parameter->getName();
        }

        $methodBody = '$this->' . $initializerProperty->getName() . "->initialize(\$this->" . $lazyPropertiesProperty->getName() . ", \"" . $method->getName() . "\", \$this->" . $wrappedObjectProperty->getName() . ");\n\n"
            . 'return $this->' . $wrappedObjectProperty->getName() . '->' . $method->getName() .  '(' . implode(', ', $forwardedParams) . ');';


        $method->setDocblock('{@inheritDoc}');
        $method->setBody(
            $methodBody
        );

        return $method;
    }
}
