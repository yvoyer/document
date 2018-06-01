<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Exception\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class DocumentDesignerAggregateTest extends TestCase
{
    /**
     * @var DocumentDesignerAggregate
     */
    private $document;

    public function setUp()
    {
        $this->document = new DocumentDesignerAggregate(new DocumentId('id'));
    }

    public function test_it_should_publish_document()
    {
        $this->assertFalse($this->document->isPublished());

        $this->document->publish();

        $this->assertTrue($this->document->isPublished());
    }

    public function test_it_should_create_property()
    {
        $this->assertAttributeCount(0, 'properties', $this->document);

        $name = 'name';
        $this->document->createProperty(new PropertyDefinition($name, new NullType()));

        $this->assertInstanceOf(
            PropertyDefinition::class,
            $definition = $this->document->getPropertyDefinition($name)
        );
        $this->assertEquals(new PropertyName($name), $definition->getName());
    }

    public function test_it_should_visit_the_document()
    {
        $visitor = $this->createMock(DocumentVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitDocument');

        $this->document->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_visit_the_document_properties()
    {
        $this->document->createProperty(
            new PropertyDefinition('name', new NullType())
        );
        $visitor = $this->createMock(DocumentVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitProperty');

        $this->document->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_add_a_constraint()
    {
        $name = new PropertyName('name');
        $definition = new PropertyDefinition($name->toString(), new NullType());
        $this->document->createProperty($definition);

        $this->assertFalse($this->document->getPropertyDefinition('name')->hasConstraint('const'));

        $this->document->addConstraint(
            $name,
            'const',
            $this->createMock(PropertyConstraint::class)
        );

        $this->assertTrue($this->document->getPropertyDefinition('name')->hasConstraint('const'));
    }

    public function test_it_should_remove_the_constraint()
    {
        $name = new PropertyName('name');
        $definition = new PropertyDefinition($name->toString(), new NullType());
        $definition = $definition->addConstraint('const', $this->createMock(PropertyConstraint::class));
        $this->document->createProperty($definition);

        $this->assertTrue($this->document->getPropertyDefinition('name')->hasConstraint('const'));

        $this->document->removeConstraint($name, 'const');

        $this->assertFalse($this->document->getPropertyDefinition('name')->hasConstraint('const'));
    }

    public function test_it_should_throw_exception_when_property_not_defined()
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->document->getPropertyDefinition('not found');
    }
}
