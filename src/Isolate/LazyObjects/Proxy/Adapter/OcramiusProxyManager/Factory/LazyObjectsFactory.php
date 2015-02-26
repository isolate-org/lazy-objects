<?php

namespace Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\ProxyGenerator\LazyObjectsProxyGenerator;
use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\MethodReplacement;
use ProxyManager\Configuration;
use ProxyManager\Generator\ClassGenerator;
use ProxyManager\Version;
use ReflectionClass;

/**
 * Factory responsible of producing proxy lazy objects
 */
class LazyObjectsFactory
{
    const GENERATE_NEVER = 0;
    const GENERATE_WHEN_NOT_EXISTS = 1;
    const GENERATE_ALWAYS = 2;
    /**
     * @var \ProxyManager\Configuration
     */
    private $configuration;

    /**
     * Cached checked class names
     *
     * @var string[]
     */
    private $checkedClasses = array();

    /**
     * @var LazyObjectsProxyGenerator|null
     */
    private $generator;

    /**
     * @var int
     */
    private $generationStrategy;

    /**
     * @param \ProxyManager\Configuration $configuration
     * @param int $generationStrategy
     */
    public function __construct(Configuration $configuration = null, $generationStrategy = self::GENERATE_WHEN_NOT_EXISTS)
    {
        if ($generationStrategy !== self::GENERATE_NEVER && $generationStrategy !== self::GENERATE_WHEN_NOT_EXISTS
            && $generationStrategy !== self::GENERATE_ALWAYS) {
            throw new \InvalidArgumentException("Invalid generation strategy.");
        }

        $this->configuration = $configuration ?: new Configuration();
        $this->generator = new LazyObjectsProxyGenerator();
        $this->generationStrategy = $generationStrategy;
    }

    /**
     * @param $instance
     * @param array $lazyProperties
     * @param array $methodReplacements
     * @return \Isolate\LazyObjects\WrappedObject
     * @throws InvalidArgumentException
     */
    public function createProxy($instance, array $lazyProperties = array(), array $methodReplacements = [])
    {
        foreach ($lazyProperties as $lazyProperty) {
            if (!$lazyProperty instanceof LazyProperty) {
                throw new InvalidArgumentException("Lazy property needs to be an instance of Isolate\\LazyObjects\\Proxy\\LazyProperty");
            }
        }

        foreach ($methodReplacements as $methodReplacement) {
            if (!$methodReplacement instanceof MethodReplacement) {
                throw new InvalidArgumentException("Method replacement needs to be an instance of Isolate\\LazyObjects\\Proxy\\MethodReplacement");
            }
        }

        $proxyClassName = $this->generateProxy(get_class($instance));

        return new $proxyClassName($instance, $lazyProperties, $methodReplacements);
    }

    /**
     * Return proxy class name
     *
     * @param string $className
     * @return string
     */
    public function createProxyClass($className)
    {
        return $this->generateProxy($className);
    }

    /**
     * Generate a proxy from a class name
     * @param  string $className
     * @return string proxy class name
     */
    private function generateProxy($className)
    {
        if (isset($this->checkedClasses[$className])) {
            return $this->checkedClasses[$className];
        }

        $proxyParameters = array(
            'className'           => $className,
            'factory'             => get_class($this),
            'proxyManagerVersion' => Version::VERSION
        );
        $proxyClassName  = $this
            ->configuration
            ->getClassNameInflector()
            ->getProxyClassName($className, $proxyParameters);

        $this->generateProxyClass($proxyClassName, $className, $proxyParameters);

        $this
            ->configuration
            ->getSignatureChecker()
            ->checkSignature(new ReflectionClass($proxyClassName), $proxyParameters);

        return $this->checkedClasses[$className] = $proxyClassName;
    }

    /**
     * Generates the provided `$proxyClassName` from the given `$className` and `$proxyParameters`
     * @param string $proxyClassName
     * @param string $className
     * @param array  $proxyParameters
     *
     * @return void
     */
    private function generateProxyClass($proxyClassName, $className, array $proxyParameters)
    {
        if ($this->generationStrategy === self::GENERATE_NEVER) {
            return ;
        }

        if ($this->generationStrategy === self::GENERATE_WHEN_NOT_EXISTS && class_exists($proxyClassName)) {
            return ;
        }

        $className = $this->configuration->getClassNameInflector()->getUserClassName($className);
        $phpClass  = new ClassGenerator($proxyClassName);

        $this->generator->generate(new ReflectionClass($className), $phpClass);

        $phpClass = $this->configuration->getClassSignatureGenerator()->addSignature($phpClass, $proxyParameters);

        $this->configuration->getGeneratorStrategy()->generate($phpClass);
        $this->configuration->getProxyAutoloader()->__invoke($proxyClassName);
    }
}

