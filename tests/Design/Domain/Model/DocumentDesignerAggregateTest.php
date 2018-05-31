<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
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

        $name = new PropertyName('name');
        $this->document->createProperty(new PropertyDefinition($name, new NullType()));

        $this->assertInstanceOf(
            PropertyDefinition::class,
            $definition = $this->document->getPropertyDefinition($name->toString())
        );
        $this->assertEquals($name, $definition->getName());
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
            new PropertyDefinition(new PropertyName('name'), new NullType())
        );
        $visitor = $this->createMock(DocumentVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitProperty');

        $this->document->acceptDocumentVisitor($visitor);
    }
}
