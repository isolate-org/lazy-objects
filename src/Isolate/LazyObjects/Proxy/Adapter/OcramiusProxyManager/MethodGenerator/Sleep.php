<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use Zend\Code\Generator\PropertyGenerator;

class Sleep extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(
        PropertyGenerator $wrappedObjectProperty,
        PropertyGenerator $initializerProperty,
        PropertyGenerator $lazyPropertiesProperty,
        PropertyGenerator $methodReplacementsProperty
    ) {
        parent::__construct('__sleep');
        $wrappedObject = $wrappedObjectProperty->getName();
        $initializer = $initializerProperty->getName();
        $lazyProperties = $initializerProperty->getName();
        $methodReplacements  = $methodReplacementsProperty->getName();

        $this->setBody(sprintf(
            "return array(\"%s\", \"%s\", \"%s\", \"%s\");",
            $wrappedObject,
            $initializer,
            $lazyProperties,
            $methodReplacements
        ));
    }
}
