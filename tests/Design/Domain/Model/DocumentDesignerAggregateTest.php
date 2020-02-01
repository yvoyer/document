<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Exception\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;

final class DocumentDesignerAggregateTest extends TestCase
{
    /**
     * @var DocumentDesignerAggregate
     */
    private $document;

    public function setUp(): void
    {
        $this->document = DocumentDesignerAggregate::draft(DocumentId::fromString('id'));
    }

    public function test_it_should_publish_document(): void
    {
        $this->assertFalse($this->document->isPublished());

        $this->document->addProperty(
            PropertyName::fixture(),
            new NullType(),
            new Constraints\RequiresValue()
        );
        $this->document->publish();

        $this->assertTrue($this->document->isPublished());
    }

    public function test_it_should_create_property(): void
    {
        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertCount(0, $visitor->properties());

        $name = PropertyName::fromString('name');
        $this->document->addProperty($name, new NullType(), new Constraints\NoConstraint());

        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertCount(1, $visitor->properties());
        $definition = $this->document->getPropertyDefinition($name);
        $this->assertSame('name', $definition->getName()->toString());
    }

    public function test_it_should_visit_the_document(): void
    {
        $visitor = $this->createMock(DocumentVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitDocument');

        $this->document->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_visit_the_document_properties(): void
    {
        $this->document->addProperty(
            PropertyName::fixture(),
            new NullType(),
            new Constraints\NoConstraint()
        );
        $visitor = $this->createMock(DocumentVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitProperty');

        $this->document->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_add_a_constraint(): void
    {
        $name = PropertyName::fixture();
        $this->document->addProperty(
            $name,
            new NullType(),
            new Constraints\RequiresValue()
        );

        $this->assertFalse($this->document->getPropertyDefinition($name)->hasConstraint('const'));

        $this->document->addPropertyConstraint(
            $name,
            'const',
            $this->createMock(PropertyConstraint::class)
        );

        $this->assertTrue($this->document->getPropertyDefinition($name)->hasConstraint('const'));
    }

    public function test_it_should_throw_exception_when_property_not_defined(): void
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->document->getPropertyDefinition(PropertyName::fromString('not found'));
    }

    public function test_it_should_add_transformer_on_property(): void
    {
        $name = PropertyName::fixture();
        $identifier = TransformerIdentifier::random();
        $this->document->addProperty($name, new NullType(), new Constraints\NoConstraint());

        $this->assertFalse($this->document->getPropertyDefinition($name)->hasTransformer($identifier));

        $this->document->addPropertyTransformer($name, $identifier);

        $this->assertTrue($this->document->getPropertyDefinition($name)->hasTransformer($identifier));
    }
}
