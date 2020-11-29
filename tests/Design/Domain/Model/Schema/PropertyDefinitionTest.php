<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Schema;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Constraints\ClosureConstraint;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class PropertyDefinitionTest extends TestCase
{
    public function test_it_should_create_property_with_type(): void
    {
        $definition = new PropertyDefinition(PropertyName::fromString('name'), new NullType());
        $this->assertSame('name', $definition->getName()->toString());
        $this->assertSame('null', $definition->toTypedString());
    }

    public function test_it_should_add_constraint(): void
    {
        $definition = new PropertyDefinition(PropertyName::fromString('name'), new NullType());
        $new = $definition->addConstraint(
            'const',
            new ClosureConstraint(
                function ($name, $value, ErrorList $errors) {
                    $errors->addError($name, 'en', 'bad value');
                }
            )
        );

        $new->validateValue(new EmptyValue(), $errors = new ErrorList());
        $this->assertTrue($errors->hasErrors());
    }

    public function test_it_should_use_the_name_of_the_callee_when_merging_definitions(): void
    {
        $callee = new PropertyDefinition(PropertyName::fromString('callee'), new NullType());
        $argument = new PropertyDefinition(
            PropertyName::fromString('argument'),
            $this->createMock(PropertyType::class)
        );
        $new = $callee->merge($argument);

        $this->assertInstanceOf(PropertyDefinition::class, $new);
        $this->assertSame('callee', $new->getName()->toString());
    }

    public function test_it_should_use_the_type_of_the_callee_when_merging_definitions(): void
    {
        $callee = new PropertyDefinition(PropertyName::fromString('callee'), new NullType());
        $argument = new PropertyDefinition(
            PropertyName::fromString('argument'),
            $this->createMock(PropertyType::class)
        );
        $new = $callee->merge($argument);

        $this->assertInstanceOf(PropertyDefinition::class, $new);
        $this->assertSame('null', $new->toTypedString());
    }

    public function test_it_should_merge_constraints_when_merging_definitions(): void
    {
        $callee = (new PropertyDefinition(PropertyName::fromString('callee'), new NullType()))
            ->addConstraint('const', $constraint = ClosureConstraint::nullConstraint());
        $argument = new PropertyDefinition(
            PropertyName::fromString('argument'),
            new NullType()
        );
        $this->assertTrue($callee->hasConstraint('const'));
        $this->assertFalse($argument->hasConstraint('const'));

        $new = $callee->merge($argument);

        $this->assertTrue($new->hasConstraint('const'));
        $this->assertSame($constraint, $new->getConstraint('const'));
    }

    public function test_it_should_use_the_given_definition_when_overriding_a_constraint_on_merge(): void
    {
        $callee = (new PropertyDefinition(PropertyName::fromString('callee'), new NullType()))
            ->addConstraint('const', $calleeConstraint = ClosureConstraint::nullConstraint());
        $definition = new PropertyDefinition(PropertyName::fromString('argument'), new NullType());
        $argument = $definition->addConstraint('const', $argumentConstraint = ClosureConstraint::nullConstraint());

        $this->assertTrue($callee->hasConstraint('const'));
        $this->assertTrue($argument->hasConstraint('const'));

        $new = $callee->merge($argument);

        $this->assertTrue($new->hasConstraint('const'));
        $this->assertSame($argumentConstraint, $new->getConstraint('const'));
    }

    public function test_it_should_stop_visiting_on_visit_property(): void
    {
        $definition = new PropertyDefinition(PropertyName::fromString('name'), new NullType());
        /**
         * @var PropertyDefinition $definition
         */
        $definition = $definition->addConstraint('const', new NoConstraint());

        $visitor = $this->createMock(DocumentVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitProperty')
            ->willReturn(true);
        $visitor
            ->expects($this->never())
            ->method('visitPropertyConstraint');
        $definition->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_add_parameter(): void
    {
        $definition = new PropertyDefinition(PropertyName::fixture(), new NullType());
        $this->assertFalse($definition->hasParameter('param'));

        $new = $definition->addParameter('param', new NullParameter());

        $this->assertTrue($new->hasParameter('param'));
        $this->assertFalse($definition->hasParameter('param'));
    }
}
