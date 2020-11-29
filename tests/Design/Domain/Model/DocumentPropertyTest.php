<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Constraints\All;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentProperty;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class DocumentPropertyTest extends TestCase
{
    /**
     * @var DocumentProperty
     */
    private $property;

    public function setUp(): void
    {
        $this->property = new DocumentProperty(
            new PropertyDefinition(PropertyName::fromString('name'), new NullType())
        );
    }

    public function test_it_should_match_definition_name(): void
    {
        $this->assertTrue($this->property->matchName(PropertyName::fromString('name')));
        $this->assertFalse($this->property->matchName(PropertyName::fromString('not name')));
    }

    public function test_it_should_add_constraint(): DocumentProperty
    {
        $this->assertFalse($this->property->getDefinition()->hasConstraint('name'));

        $this->property->addConstraint('name', new All(new NoConstraint()));

        $this->assertTrue($this->property->getDefinition()->hasConstraint('name'));

        return $this->property;
    }

    /**
     * @depends test_it_should_add_constraint
     *
     * @param DocumentProperty $property
     */
    public function test_it_should_remove_constraint(DocumentProperty $property): void
    {
        $this->assertTrue($property->getDefinition()->hasConstraint('name'));

        $property->removeConstraint('name');

        $this->assertFalse($property->getDefinition()->hasConstraint('name'));
    }
}
