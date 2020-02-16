<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Design\Domain\Structure\PropertyExtractor;

final class DocumentAggregateTest extends TestCase
{
    /**
     * @var DocumentAggregate
     */
    private $document;

    public function setUp(): void
    {
        $this->document = DocumentAggregate::draft(DocumentId::fromString('id'));
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
        $name = PropertyName::fromString('name');
        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertFalse($visitor->hasProperty($name->toString()));

        $this->document->addProperty($name, new NullType());

        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertTrue($visitor->hasProperty($name->toString()));
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
        $this->document->addProperty(PropertyName::fixture(), new NullType());
        $visitor = $this->createMock(DocumentVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitProperty');

        $this->document->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_add_a_property_constraint(): void
    {
        $name = PropertyName::fixture();
        $this->document->addProperty($name, new NullType());

        $this->assertFalse($this->document->getPropertyDefinition($name)->hasConstraint('const'));

        $constraint = new Constraints\All('const', new Constraints\NoConstraint());
        $this->document->addPropertyConstraint($name, $constraint);

        $this->assertTrue($this->document->getPropertyDefinition($name)->hasConstraint('const'));
    }

    public function test_it_should_throw_exception_when_property_not_defined(): void
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->document->getPropertyDefinition(PropertyName::fromString('not found'));
    }

    public function test_it_should_add_a_constraint_on_document(): void
    {
        $constraint = $this->createMock(DocumentConstraint::class);

        $this->assertNotSame($constraint, $this->document->getConstraint());

        $this->document->setDocumentConstraint($constraint);

        $this->assertSame($constraint, $this->document->getConstraint());
    }

    public function test_it_should_add_a_parameter_on_the_property(): void
    {
        $name = PropertyName::fixture();
        $this->document->addProperty($name, new NullType());

        $this->assertFalse(
            $this->document->getSchema()->getDefinition($name->toString())->hasParameter('name')
        );

        $this->document->addPropertyParameter($name, new NullParameter('name'));

        $this->assertTrue(
            $this->document->getSchema()->getDefinition($name->toString())->hasParameter('name')
        );
    }
}
