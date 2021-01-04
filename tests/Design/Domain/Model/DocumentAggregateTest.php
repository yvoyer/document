<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\DocumentAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\Events\DocumentCreated;
use Star\Component\Document\Design\Domain\Model\Events\PropertyConstraintWasAdded;
use Star\Component\Document\Design\Domain\Model\Events\PropertyConstraintWasRemoved;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Schema\StringDocumentType;
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
        $this->document = DocumentAggregate::draft(
            DocumentId::fromString('id'),
            new StringDocumentType('type')
        );
    }

    public function test_it_should_create_property(): void
    {
        $name = PropertyName::fromString('name');
        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertFalse($visitor->hasProperty($name->toString()));

        $this->document->addProperty($name, new NullType());

        $this->document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        $this->assertTrue($visitor->hasProperty($name->toString()));
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
        $this->document->uncommitedEvents(); // reset

        $this->assertFalse(
            $this->extractProperty($this->document)->hasPropertyConstraint($name->toString(), 'const')
        );

        $constraint = new Constraints\All(new Constraints\NoConstraint());
        $this->document->addPropertyConstraint($name, 'const', $constraint);

        $this->assertTrue(
            $this->extractProperty($this->document)->hasPropertyConstraint($name->toString(), 'const')
        );

        /**
         * @var PropertyConstraintWasAdded[] $events
         */
        $events = $this->document->uncommitedEvents();
        self::assertCount(1, $events);
        $event = $events[0];
        self::assertInstanceOf(PropertyConstraintWasAdded::class, $event);
        self::assertSame($this->document->getIdentity()->toString(), $event->documentId()->toString());
        self::assertSame('const', $event->constraintName());
        self::assertSame($name->toString(), $event->propertyName()->toString());
        self::assertInstanceOf(Constraints\All::class, $event->constraint());
    }

    public function test_it_should_remove_a_property_constraint(): void
    {
        $name = PropertyName::fixture();
        $this->document->addProperty($name, new NullType());
        $this->document->addPropertyConstraint($name, 'const', new Constraints\NoConstraint());
        $this->document->uncommitedEvents(); // reset

        $this->assertTrue(
            $this->extractProperty($this->document)->hasPropertyConstraint($name->toString(), 'const')
        );

        $this->document->removePropertyConstraint($name, 'const');

        $this->assertFalse(
            $this->extractProperty($this->document)->hasPropertyConstraint($name->toString(), 'const')
        );

        /**
         * @var PropertyConstraintWasRemoved[] $events
         */
        $events = $this->document->uncommitedEvents();
        self::assertCount(1, $events);
        $event = $events[0];
        self::assertInstanceOf(PropertyConstraintWasRemoved::class, $event);
        self::assertSame($this->document->getIdentity()->toString(), $event->documentId()->toString());
        self::assertSame($name->toString(), $event->propertyName()->toString());
        self::assertSame('const', $event->constraintName());
    }

    public function test_it_should_throw_exception_when_property_not_defined(): void
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "not found" could not be found.');
        $this->document->removePropertyConstraint(PropertyName::fromString('not found'), 'const');
    }

    public function test_it_should_add_a_constraint_on_a_document_for_validation(): void
    {
        self::assertFalse($this->extractProperty($this->document)->hasDocumentConstraint('name'));

        $constraint = new Constraints\NoConstraint();
        $this->document->addDocumentConstraint('name', $constraint);

        self::assertTrue($this->extractProperty($this->document)->hasDocumentConstraint('name'));
    }

    public function test_it_should_add_a_parameter_on_the_property(): void
    {
        $parameter = new NullParameter();
        $name = PropertyName::fixture();
        $this->document->addProperty($name, new NullType());

        $this->assertFalse(
            $this->document->getSchema()->getPropertyMetadata($name->toString())->hasParameter('param')
        );

        $this->document->addPropertyParameter($name, 'param', $parameter);

        $this->assertTrue(
            $this->document->getSchema()->getPropertyMetadata($name->toString())->hasParameter('param')
        );
    }

    private function extractProperty(DocumentAggregate $document): PropertyExtractor
    {
        $document->acceptDocumentVisitor($visitor = new PropertyExtractor());
        return $visitor;
    }

    public function test_it_should_have_a_type(): void
    {
        /**
         * @var DocumentCreated[] $events
         */
        $events = $this->document->uncommitedEvents();
        self::assertCount(1, $events);
        $event = $events[0];
        self::assertInstanceOf(DocumentCreated::class, $event);
        self::assertSame($this->document->getIdentity()->toString(), $event->documentId()->toString());
        self::assertSame('type', $event->documentType()->toString());
    }
}
