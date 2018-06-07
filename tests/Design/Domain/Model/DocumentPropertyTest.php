<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class DocumentPropertyTest extends TestCase
{
    /**
     * @var DocumentProperty
     */
    private $property;

    public function setUp()
    {
        $this->property = DocumentProperty::fromDefinition(
            $this->createMock(DocumentDesigner::class),
            new PropertyDefinition('name', new NullType())
        );
    }

    public function test_it_should_match_definition_name()
    {
        $this->assertTrue($this->property->matchName(new PropertyName('name')));
        $this->assertFalse($this->property->matchName(new PropertyName('not name')));
    }

    public function test_it_should_add_constraint()
    {
        $this->assertFalse($this->property->getDefinition()->hasConstraint('name'));

        $this->property->addConstraint('name', $this->createMock(PropertyConstraint::class));

        $this->assertTrue($this->property->getDefinition()->hasConstraint('name'));

        return $this->property;
    }

    /**
     * @depends test_it_should_add_constraint
     *
     * @param DocumentProperty $property
     */
    public function test_it_should_remove_constraint(DocumentProperty $property)
    {
        $this->assertTrue($property->getDefinition()->hasConstraint('name'));

        $property->removeConstraint('name');

        $this->assertFalse($property->getDefinition()->hasConstraint('name'));
    }
}
