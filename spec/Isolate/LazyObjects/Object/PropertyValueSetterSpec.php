<?php

namespace spec\Isolate\LazyObjects\Object;

use Isolate\LazyObjects\Exception\InvalidArgumentException;
use Isolate\LazyObjects\Exception\NotExistingPropertyException;
use Isolate\LazyObjects\Object\Value\Assembler;
use Isolate\LazyObjects\Object\Value\AssemblerFactory;
use PhpSpec\Exception\Example\ExampleException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PropertyValueSetterSpec extends ObjectBehavior
{
    public function let(AssemblerFactory $factory, Assembler $assembler)
    {
        $factory->createFor(Argument::any(), Argument::any())->willReturn($assembler);
        $this->beConstructedWith($factory);
    }

    function it_throw_exception_on_attempt_to_access_property_not_on_object()
    {
        $this->shouldThrow(
            new InvalidArgumentException("PropertyAccessor require object to access property, \"array\" passed.")
        )->during("set", [[], "value", "property"]);
    }

    function it_throw_exception_when_accessing_not_existing_property()
    {
        $object = new Entity();
        $this->shouldThrow(
            new NotExistingPropertyException("Property \"notExistingPropertyName\" does not exists in \"spec\\Isolate\\LazyObjects\\Object\\Entity\" class.")
        )->during("set", [$object, "notExistingPropertyName", "value"]);
    }

    function it_use_value_generator_strategy_before_setting_new_value(Assembler $assembler)
    {
        $assembler->assemble("Norbert", null)->willReturn("Norbert+");
        $entity = new Entity();
        $this->set($entity, "name", "Norbert");
        if ($entity->getName() !== "Norbert+") {
            throw new ExampleException("Expected \$name property to have value \"Norbert+\".");
        }
    }
}

class Entity
{
    private $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
