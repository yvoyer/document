<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyType;
use Star\Component\Document\Design\Domain\Model\Types\BooleanType;
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

    public function test_it_should_create_text_property()
    {
        $definition = PropertyDefinition::fromString('name', StringType::class);
        $this->assertSame('name', $definition->getName()->toString());
        $this->assertInstanceOf(StringType::class, $definition->getType());
    }

    public function test_it_should_create_a_required_property()
    {
        $definition = PropertyDefinition::fromString('name', NullType::class);
        $this->assertFalse($definition->isRequired());
        $definition->setMandatory(true);
        $this->assertTrue($definition->isRequired());
    }

    public function test_it_should_create_a_boolean_property()
    {
        $definition = PropertyDefinition::fromString('name', BooleanType::class);
        $this->assertSame('name', $definition->getName()->toString());
        $this->assertInstanceOf(BooleanType::class, $definition->getType());
    }
}
