<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator;

use ProxyManager\Generator\MethodGenerator;
use ProxyManager\Generator\ParameterGenerator;
use ReflectionClass;
use ReflectionProperty;
use Zend\Code\Generator\PropertyGenerator;

class Constructor extends MethodGenerator
{
    /**
     * Constructor
     */
    public function __construct(
        ReflectionClass $originalClass,
        PropertyGenerator $wrappedObjectProperty,
        PropertyGenerator $lazyPropertiesProperty,
        PropertyGenerator $initializerProperty
    ) {
        parent::__construct('__construct');

        $lazyPropertiesArg = new ParameterGenerator('lazyProperties');
        $lazyPropertiesArg->setDefaultValue([]);
        $lazyPropertiesArg->setType('array');

        $this->setParameter(new ParameterGenerator('wrappedObject'));
        $this->setParameter($lazyPropertiesArg);

        $this->setBody(
              '$this->' . $wrappedObjectProperty->getName() . " = \$wrappedObject;\n"
            . '$this->' . $lazyPropertiesProperty->getName() . " = \$lazyProperties;\n"
            . '$this->' . $initializerProperty->getName() . " = new \\Isolate\\LazyObjects\\Object\\Property\\Initializer();\n"
            . '$this->' . $initializerProperty->getName() . "->initialize(\$this->" . $lazyPropertiesProperty->getName() . ", \"__construct\", \$this->" . $wrappedObjectProperty->getName() . ");\n"
        );
    }
}
