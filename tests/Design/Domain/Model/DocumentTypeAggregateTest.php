<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\Events\DocumentTypeWasCreated;
use Star\Component\Document\Design\Domain\Model\Events\PropertyConstraintWasAdded;
use Star\Component\Document\Design\Domain\Model\Events\PropertyConstraintWasRemoved;
use Star\Component\Document\Design\Domain\Model\DocumentName;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class DocumentTypeAggregateTest extends TestCase
{
    private DocumentTypeAggregate $type;

    public function setUp(): void
    {
        $this->type = DocumentTypeAggregate::draft(
            DocumentTypeId::fromString('id'),
            DocumentName::fromLocalizedString('name', 'en'),
            new NullOwner(),
            new DateTimeImmutable()
        );
    }

    public function test_it_should_create_property(): void
    {
        $code = PropertyCode::fromString('code');
        $this->assertFalse($this->type->propertyExists($code));

        $this->type->addProperty(
            $code,
            PropertyName::fromLocalizedString('name', 'en'),
            new NullType(),
            new DateTimeImmutable()
        );

        $this->assertTrue($this->type->propertyExists($code));
    }

    public function test_it_should_visit_the_document(): void
    {
        $visitor = $this->createMock(DocumentTypeVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitDocumentType');

        $this->type->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_visit_the_document_properties(): void
    {
        $this->type->addProperty(
            PropertyCode::random(),
            PropertyName::random(),
            new NullType(),
            new DateTimeImmutable()
        );
        $visitor = $this->createMock(DocumentTypeVisitor::class);
        $visitor
            ->expects($this->once())
            ->method('visitProperty');

        $this->type->acceptDocumentVisitor($visitor);
    }

    public function test_it_should_add_a_property_constraint(): void
    {
        $this->type->addProperty(
            $code = PropertyCode::random(),
            PropertyName::random(),
            new NullType(),
            new DateTimeImmutable()
        );
        $this->type->uncommitedEvents(); // reset

        $this->assertFalse($this->type->propertyConstraintExists($code, 'const'));

        $constraint = new Constraints\All(new Constraints\NoConstraint());
        $this->type->addPropertyConstraint(
            $code,
            'const',
            $constraint,
            new DateTimeImmutable()
        );

        $this->assertTrue($this->type->propertyConstraintExists($code, 'const'));

        /**
         * @var PropertyConstraintWasAdded[] $events
         */
        $events = $this->type->uncommitedEvents();
        self::assertCount(1, $events);
        $event = $events[0];
        self::assertInstanceOf(PropertyConstraintWasAdded::class, $event);
        self::assertSame($this->type->getIdentity()->toString(), $event->documentId()->toString());
        self::assertSame('const', $event->constraintName());
        self::assertSame($code->toString(), $event->propertyCode()->toString());
        self::assertInstanceOf(Constraints\All::class, $event->constraint());
    }

    public function test_it_should_remove_a_property_constraint(): void
    {
        $this->type->addProperty(
            $code = PropertyCode::random(),
            PropertyName::random(),
            new NullType(),
            new DateTimeImmutable()
        );
        $this->type->addPropertyConstraint(
            $code,
            'const',
            new Constraints\NoConstraint(),
            new DateTimeImmutable()
        );
        $this->type->uncommitedEvents(); // reset

        $this->assertTrue($this->type->propertyConstraintExists($code, 'const'));

        $this->type->removePropertyConstraint($code, 'const');

        $this->assertFalse($this->type->propertyConstraintExists($code, 'const'));

        /**
         * @var PropertyConstraintWasRemoved[] $events
         */
        $events = $this->type->uncommitedEvents();
        self::assertCount(1, $events);
        $event = $events[0];
        self::assertInstanceOf(PropertyConstraintWasRemoved::class, $event);
        self::assertSame($this->type->getIdentity()->toString(), $event->documentId()->toString());
        self::assertSame($code->toString(), $event->propertyCode()->toString());
        self::assertSame('const', $event->constraintName());
    }

    public function test_it_should_throw_exception_when_property_not_defined(): void
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with code "not found" could not be found.');
        $this->type->removePropertyConstraint(PropertyCode::fromString('not found'), 'const');
    }

    public function test_it_should_add_a_constraint_on_a_document_for_validation(): void
    {
        self::assertFalse($this->type->documentConstraintExists('name'));

        $constraint = new Constraints\NoConstraint();
        $this->type->addDocumentConstraint('name', $constraint);

        self::assertTrue($this->type->documentConstraintExists('name'));
    }

    public function test_it_should_add_a_parameter_on_the_property(): void
    {
        $parameter = new NullParameter();
        $this->type->addProperty(
            $code = PropertyCode::random(),
            PropertyName::random(),
            new NullType(),
            new DateTimeImmutable()
        );

        $this->assertFalse(
            $this->type->getSchema()->getPropertyMetadata($code->toString())->hasParameter('param')
        );

        $this->type->addPropertyParameter($code, 'param', $parameter, new DateTimeImmutable());

        $this->assertTrue(
            $this->type->getSchema()->getPropertyMetadata($code->toString())->hasParameter('param')
        );
    }

    public function test_it_should_have_a_type(): void
    {
        /**
         * @var DocumentTypeWasCreated[] $events
         */
        $events = $this->type->uncommitedEvents();
        self::assertCount(1, $events);
        $event = $events[0];
        self::assertInstanceOf(DocumentTypeWasCreated::class, $event);
        self::assertSame($this->type->getIdentity()->toString(), $event->documentId()->toString());
        self::assertSame('name', $event->name()->toString());
        self::assertSame('en', $event->name()->locale());
    }
}
