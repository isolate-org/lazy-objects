<?php

namespace Isolate\LazyObjects\Tests\Double\GeneratorStrategy;

use ProxyManager\FileLocator\FileLocatorInterface;
use ProxyManager\GeneratorStrategy\FileWriterGeneratorStrategy;
use Zend\Code\Generator\ClassGenerator;

final class FileWriterStrategyCounter extends FileWriterGeneratorStrategy
{
    protected $generationCount;

    public function __construct(FileLocatorInterface $fileLocator)
    {
        $this->generationCount = [];
        parent::__construct($fileLocator);
    }

    public function generate(ClassGenerator $classGenerator)
    {
        $className = $classGenerator->getNamespaceName() . '\\' . $classGenerator->getName();
        if (!array_key_exists($className, $this->generationCount)) {
            $this->generationCount[$className] = 0;
        }

        $this->generationCount[$className]++;

        return parent::generate($classGenerator);
    }

    /**
     * @param $className
     */
    public function getClassGenerationCount($className)
    {
        if (!array_key_exists($className, $this->generationCount)) {
            return 0;
        }

        return $this->generationCount[$className];
    }

    public function reset()
    {
        $this->generationCount = [];
    }
}
