<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\ProxyGenerator;

use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator\Constructor;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator\GetLazyProperties;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator\GetWrappedObject;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\MethodGenerator\MethodProxy;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\PropertyGenerator\LazyProperties;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\PropertyGenerator\Initializer;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\PropertyGenerator\WrappedObject;
use ProxyManager\Generator\Util\ClassGeneratorUtils;
use ProxyManager\ProxyGenerator\Assertion\CanProxyAssertion;
use ProxyManager\ProxyGenerator\ProxyGeneratorInterface;
use ProxyManager\ProxyGenerator\Util\ProxiedMethodsFilter;
use ReflectionClass;
use ReflectionMethod;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Reflection\MethodReflection;

class LazyObjectsProxyGenerator implements ProxyGeneratorInterface
{
    public function generate(ReflectionClass $originalClass, ClassGenerator $classGenerator)
    {
        CanProxyAssertion::assertClassCanBeProxied($originalClass);

        $interfaces = ['Isolate\\LazyObjects\\WrappedObject'];

        if ($originalClass->isInterface()) {
            $interfaces[] = $originalClass->getName();
        } else {
            $classGenerator->setExtendedClass($originalClass->getName());
        }

        $classGenerator->setImplementedInterfaces($interfaces);
        $classGenerator->addPropertyFromGenerator($wrappedObjectProperty = new WrappedObject());
        $classGenerator->addPropertyFromGenerator($lazyPropertiesProperty = new LazyProperties());
        $classGenerator->addPropertyFromGenerator($initializerProperty = new Initializer());

        array_map(
            function (MethodGenerator $generatedMethod) use ($originalClass, $classGenerator) {
                ClassGeneratorUtils::addMethodIfNotFinal($originalClass, $classGenerator, $generatedMethod);
            },
            array_merge(
                array_map(
                    function (ReflectionMethod $method) use ($wrappedObjectProperty, $lazyPropertiesProperty, $initializerProperty) {
                        return MethodProxy::generateMethod(
                            new MethodReflection($method->getDeclaringClass()->getName(), $method->getName()),
                            $wrappedObjectProperty,
                            $lazyPropertiesProperty,
                            $initializerProperty
                        );
                    },
                    ProxiedMethodsFilter::getProxiedMethods($originalClass)
                ),
                [
                    new Constructor($originalClass, $wrappedObjectProperty, $lazyPropertiesProperty, $initializerProperty),
                    new GetWrappedObject($wrappedObjectProperty),
                    new GetLazyProperties($lazyPropertiesProperty),
                ]
            )
        );
    }
}
