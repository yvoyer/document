<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyType;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class PropertyDefinitionTest extends TestCase
{
    public function test_it_should_throw_exception_when_type_class_do_not_exists()
    {
        $this->expectException(InvalidPropertyType::class);
        $this->expectExceptionMessage(
            "The class 'not-found' is not a valid class implementing interface "
            . "'Star\Component\Document\Design\Domain\Model\PropertyType'."
        );
        PropertyDefinition::fromString('name', 'not-found');
    }

    public function test_it_should_create_property_with_type()
    {
        $definition = new PropertyDefinition('name', new NullType());
        $this->assertSame('name', $definition->getName()->toString());
        $this->assertInstanceOf(PropertyType::class, $definition->getType());
        $this->assertInstanceOf(NullType::class, $definition->getType());
    }   #

    public function test_it_should_add_constraint()
    {
        $definition = new PropertyDefinition('name', new NullType());
        $new = $definition->addConstraint('const', $constraint = $this->createMock(PropertyConstraint::class));
        $this->assertInstanceOf(PropertyDefinition::class, $new);
        $constraint
            ->expects($this->once())
            ->method('validate')
            ->with($this->isInstanceOf(PropertyDefinition::class), '');

        $new->validateRawValue('');
    }

    public function test_it_should_use_the_name_of_the_callee_when_merging_definitions()
    {
        $callee = new PropertyDefinition('callee', new NullType());
        $argument = new PropertyDefinition('argument', $this->createMock(PropertyType::class));
        $new = $callee->merge($argument);

        $this->assertInstanceOf(PropertyDefinition::class, $new);
        $this->assertSame('callee', $new->getName()->toString());
    }

    public function test_it_should_use_the_type_of_the_callee_when_merging_definitions()
    {
        $callee = new PropertyDefinition('callee', new NullType());
        $argument = new PropertyDefinition('argument', $this->createMock(PropertyType::class));
        $new = $callee->merge($argument);

        $this->assertInstanceOf(PropertyDefinition::class, $new);
        $this->assertSame('null', $new->getType()->toString());
    }

    public function test_it_should_merge_constraints_when_merging_definitions()
    {
        $callee = (new PropertyDefinition('callee', new NullType()))
            ->addConstraint(
                'const',
                $constraint = $this->createMock(PropertyConstraint::class)
            );
        $argument = new PropertyDefinition('argument', $this->createMock(PropertyType::class));
        $this->assertTrue($callee->hasConstraint('const'));
        $this->assertFalse($argument->hasConstraint('const'));

        $new = $callee->merge($argument);

        $this->assertInstanceOf(PropertyDefinition::class, $new);
        $this->assertTrue($new->hasConstraint('const'));
        $this->assertSame($constraint, $new->getConstraint('const'));
    }

    public function test_it_should_use_the_given_definition_when_overriding_a_constraint_on_merge()
    {
        $callee = (new PropertyDefinition('callee', new NullType()))
            ->addConstraint(
                'const',
                $calleeConstraint = $this->createMock(PropertyConstraint::class)
            );
        $argument = (new PropertyDefinition('argument', $this->createMock(PropertyType::class)))
            ->addConstraint(
                'const',
                $argumentConstraint = $this->createMock(PropertyConstraint::class)
            );
        $this->assertTrue($callee->hasConstraint('const'));
        $this->assertTrue($argument->hasConstraint('const'));

        $new = $callee->merge($argument);

        $this->assertInstanceOf(PropertyDefinition::class, $new);
        $this->assertTrue($new->hasConstraint('const'));
        $this->assertSame($argumentConstraint, $new->getConstraint('const'));
    }
}
