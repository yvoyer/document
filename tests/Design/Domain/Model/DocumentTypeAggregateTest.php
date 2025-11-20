<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\Constraints;
use Star\Component\Document\Design\Domain\Model\DocumentTypeAggregate;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeName;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\Events\DocumentTypeWasCreated;
use Star\Component\Document\Design\Domain\Model\Events\DocumentTypeWasRenamed;
use Star\Component\Document\Design\Domain\Model\Events\PropertyConstraintWasAdded;
use Star\Component\Document\Design\Domain\Model\Events\PropertyConstraintWasRemoved;
use Star\Component\Document\Design\Domain\Model\InvalidDocumentTypeName;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Test\NullOwner;
use Star\Component\Document\Design\Domain\Model\Types\NullType;
use Star\Component\Document\Translation\Domain\Model\Strategy\NotAllowedEmptyTranslation;

final class DocumentTypeAggregateTest extends TestCase
{
    private DocumentTypeAggregate $type;

    public function setUp(): void
    {
        $this->type = DocumentTypeAggregate::draft(
            DocumentTypeId::fromString('id'),
            DocumentTypeName::fromLocalizedString('name', 'en'),
            new NullOwner(),
            AuditDateTime::fromNow()
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
            AuditDateTime::fromNow()
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
            AuditDateTime::fromNow()
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
            AuditDateTime::fromNow()
        );
        $this->type->uncommitedEvents(); // reset

        $this->assertFalse($this->type->propertyConstraintExists($code, 'const'));

        $constraint = new Constraints\All(new Constraints\NoConstraint());
        $this->type->addPropertyConstraint(
            $code,
            'const',
            $constraint,
            AuditDateTime::fromNow()
        );

        $this->assertTrue($this->type->propertyConstraintExists($code, 'const'));

        /**
         * @var PropertyConstraintWasAdded[] $events
         */
        $events = $this->type->uncommitedEvents();
        self::assertCount(1, $events);
        $event = $events[0];
        self::assertInstanceOf(PropertyConstraintWasAdded::class, $event);
        self::assertSame($this->type->getIdentity()->toString(), $event->typeId()->toString());
        self::assertSame('const', $event->constraintAlias());
        self::assertSame($code->toString(), $event->propertyCode()->toString());
        self::assertInstanceOf(Constraints\All::class, $event->constraintData()->createPropertyConstraint());
    }

    public function test_it_should_remove_a_property_constraint(): void
    {
        $this->type->addProperty(
            $code = PropertyCode::random(),
            PropertyName::random(),
            new NullType(),
            AuditDateTime::fromNow()
        );
        $this->type->addPropertyConstraint(
            $code,
            'const',
            new Constraints\NoConstraint(),
            AuditDateTime::fromNow()
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
        self::assertSame($this->type->getIdentity()->toString(), $event->typeId()->toString());
        self::assertSame($code->toString(), $event->propertyCode()->toString());
        self::assertSame('const', $event->constraintName());
    }

    public function test_it_should_throw_exception_when_property_not_defined(): void
    {
        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with code "not-found" could not be found.');
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
            AuditDateTime::fromNow()
        );

        $this->assertFalse(
            $this->type->getSchema()->getPropertyMetadata($code->toString())->hasParameter('param')
        );

        $this->type->addPropertyParameter($code, 'param', $parameter, AuditDateTime::fromNow());

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
        self::assertSame($this->type->getIdentity()->toString(), $event->typeId()->toString());
        self::assertSame('name', $event->name()->toString());
        self::assertSame('en', $event->name()->locale());
    }

    public function test_it_should_add_a_translation_to_a_new_locale(): void
    {
        $this->type->uncommitedEvents();
        self::assertSame('en', $this->type->getDefaultLocale());
        self::assertSame('name', $this->type->getName('fr')->toString());
        self::assertSame('name', $this->type->getName('en')->toString());

        $this->type->rename(
            DocumentTypeName::fromLocalizedString('name-fr', 'fr'),
            AuditDateTime::fromString('2000-03-04'),
            new NullOwner()
        );

        self::assertSame('en', $this->type->getDefaultLocale());
        self::assertSame('name-fr', $this->type->getName('fr')->toString());
        self::assertSame('name', $this->type->getName('en')->toString());
        $events = $this->type->uncommitedEvents();
        /**
         * @var DocumentTypeWasRenamed $event
         */
        $event = $events[0];
        self::assertInstanceOf(DocumentTypeWasRenamed::class, $event);
        self::assertSame('name', $event->oldName()->toString());
        self::assertSame('fr', $event->oldName()->locale());
        self::assertSame('name-fr', $event->newName()->toString());
        self::assertSame('fr', $event->newName()->locale());
    }

    public function test_it_should_replace_the_name_of_the_default_locale(): void
    {
        $this->type->uncommitedEvents();
        $defaultLocale = $this->type->getDefaultLocale();
        self::assertSame('name', $this->type->getName($defaultLocale)->toString());

        $this->type->rename(
            DocumentTypeName::fromLocalizedString('new-name', $defaultLocale),
            AuditDateTime::fromNow(),
            new NullOwner()
        );

        self::assertSame('new-name', $this->type->getName($defaultLocale)->toString());
        $events = $this->type->uncommitedEvents();
        /**
         * @var DocumentTypeWasRenamed $event
         */
        $event = $events[0];
        self::assertInstanceOf(DocumentTypeWasRenamed::class, $event);
        self::assertSame('name', $event->oldName()->toString());
        self::assertSame('en', $event->oldName()->locale());
        self::assertSame('new-name', $event->newName()->toString());
        self::assertSame('en', $event->newName()->locale());
    }

    public function test_it_should_replace_the_name_of_a_locale(): void
    {
        $this->type->rename(
            DocumentTypeName::fromLocalizedString('it-name', 'it'),
            AuditDateTime::fromNow(),
            new NullOwner()
        );
        $this->type->uncommitedEvents();

        $defaultLocale = $this->type->getDefaultLocale();
        self::assertSame('name', $this->type->getName($defaultLocale)->toString());
        self::assertSame('it-name', $this->type->getName('it')->toString());

        $this->type->rename(
            DocumentTypeName::fromLocalizedString('another-name', 'it'),
            AuditDateTime::fromNow(),
            new NullOwner()
        );

        self::assertSame('another-name', $this->type->getName('it')->toString());
        $events = $this->type->uncommitedEvents();
        /**
         * @var DocumentTypeWasRenamed $event
         */
        $event = $events[0];
        self::assertInstanceOf(DocumentTypeWasRenamed::class, $event);
        self::assertSame('it-name', $event->oldName()->toString());
        self::assertSame('it', $event->oldName()->locale());
        self::assertSame('another-name', $event->newName()->toString());
        self::assertSame('it', $event->newName()->locale());
    }

    public function test_it_should_remove_the_translation_to_an_existing_locale(): void
    {
        $this->type->rename(
            DocumentTypeName::fromLocalizedString('fr-name', 'fr'),
            AuditDateTime::fromNow(),
            new NullOwner()
        );
        $this->type->uncommitedEvents();

        self::assertSame(
            'name',
            $this->type->getName($defaultLocale = $this->type->getDefaultLocale())->toString()
        );
        self::assertSame('fr-name', $this->type->getName('fr')->toString());

        $this->type->rename(
            DocumentTypeName::fromLocalizedString('', 'fr'),
            AuditDateTime::fromNow(),
            new NullOwner()
        );

        self::assertSame('name', $this->type->getName($defaultLocale)->toString());
        self::assertSame('name', $this->type->getName('fr')->toString());
        $events = $this->type->uncommitedEvents();
        /**
         * @var DocumentTypeWasRenamed $event
         */
        $event = $events[0];
        self::assertInstanceOf(DocumentTypeWasRenamed::class, $event);
        self::assertSame('fr-name', $event->oldName()->toString());
        self::assertSame('fr', $event->oldName()->locale());
        self::assertSame('', $event->newName()->toString());
        self::assertSame('fr', $event->newName()->locale());
    }

    public function test_it_should_not_allow_removing_name_of_default_locale(): void
    {
        $this->expectException(InvalidDocumentTypeName::class);
        $this->expectErrorMessage('Document type name cannot be empty for the default locale "en".');
        $this->type->rename(
            DocumentTypeName::fromLocalizedString('', $this->type->getDefaultLocale()),
            AuditDateTime::fromNow(),
            new NullOwner()
        );
    }

    public function test_it_should_not_allow_empty_name_for_default_locale(): void
    {
        $this->expectException(InvalidDocumentTypeName::class);
        $this->expectErrorMessage('Document type name cannot be empty for the default locale "en".');
        DocumentTypeAggregate::draft(
            DocumentTypeId::random(),
            DocumentTypeName::fromLocalizedString('', 'en'),
            new NullOwner(),
            AuditDateTime::fromNow()
        );
    }
}
