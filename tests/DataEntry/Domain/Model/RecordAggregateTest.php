<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Events\PropertyValueWasChanged;
use Star\Component\Document\DataEntry\Domain\Model\Events\PropertyValueWasSet;
use Star\Component\Document\DataEntry\Domain\Model\Events\RecordWasCreated;
use Star\Component\Document\DataEntry\Domain\Model\RecordAggregate;
use Star\Component\Document\DataEntry\Domain\Model\RecordId;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidationFailedForProperty;
use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Builder\DocumentBuilder;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Types\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\Types\NotSupportedTypeForValue;
use function array_shift;

final class RecordAggregateTest extends TestCase
{
    public function test_it_should_set_a_property_value(): void
    {
        $record = RecordAggregate::withValues(
            RecordId::fromString('id'),
            DocumentBuilder::createDocument()
                ->createText($property = 'name')->endProperty()
                ->getSchema()
        );
        $record->setValue(
            $property,
            StringValue::fromString('value'),
            $this->createMock(StrategyToHandleValidationErrors::class)
        );
        $this->assertSame('value', $record->getValue($property)->toString());
    }

    public function test_it_should_throw_exception_when_property_never_set(): void
    {
        $record = RecordAggregate::withValues(
            RecordId::fromString('id'),
            DocumentBuilder::createDocument()->getSchema()
        );

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "name" could not be found.');
        $record->getValue('name');
    }

    public function test_it_should_throw_exception_when_setting_a_value_do_not_respect_constraint(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createText('text')->required()->endProperty()
            ->getSchema();

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage(
            'Validation error: [Property named "text" is required, but "empty()" given.]'
        );
        RecordAggregate::withValues(
            RecordId::random(),
            $schema,
            [
                'text' => StringValue::fromString(''),
            ]
        );
    }

    public function test_it_should_return_empty_value_when_not_set(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createText('optional')->endProperty()
            ->getSchema();

        $record = RecordAggregate::withValues(RecordId::random(), $schema);
        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertTrue($record->getValue('optional')->isEmpty());
    }

    public function test_it_should_return_default_value_when_set_to_empty(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createText('optional')->endProperty()
            ->getSchema();

        $record = RecordAggregate::withValues(RecordId::random(), $schema);
        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertTrue($record->getValue('optional')->isEmpty());
    }

    public function test_it_should_throw_exception_when_value_is_not_supported_by_type(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createListOfOptions('list', OptionListValue::withElements(3))->endProperty()
            ->getSchema();

        $this->expectException(NotSupportedTypeForValue::class);
        $this->expectExceptionMessage('The property "list" only supports values of type "list", "int(2000)" given.');
        RecordAggregate::withValues(
            RecordId::random(),
            $schema,
            ['list' => IntegerValue::fromInt(2000)]
        );
    }

    public function test_it_should_throw_exception_when_value_is_not_supported_by_property(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createListOfOptions('list-field', OptionListValue::withElements(2))->endProperty()
            ->getSchema();

        $this->expectException(InvalidPropertyValue::class);
        $this->expectExceptionMessage(
            'Value for property "list-field" must contain valid option ids. '
            . 'Supporting: "[{"id":1,"value":"Option 1","label":"Label 1"},'
            . '{"id":2,"value":"Option 2","label":"Label 2"}]", given "list(999)".'
        );
        RecordAggregate::withValues(
            RecordId::random(),
            $schema,
            ['list-field' => ArrayOfInteger::withValues(999)]
        );
    }

    public function test_it_should_trigger_event_when_setting_a_property_value(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createText('text')->endProperty()
            ->getSchema();
        $record = RecordAggregate::withValues(
            RecordId::random(),
            $schema,
            [
                'text' => StringValue::fromString('val'),
            ]
        );
        $events = $record->uncommitedEvents();

        self::assertCount(2, $events);
        self::assertInstanceOf(RecordWasCreated::class, array_shift($events));
        /**
         * @var PropertyValueWasSet $event
         */
        $event = array_shift($events);
        self::assertInstanceOf(PropertyValueWasSet::class, $event);
        self::assertSame($record->getIdentity()->toString(), $event->recordId()->toString());
        self::assertSame($record->getDocumentId()->toString(), $event->documentId()->toString());
        self::assertSame('text', $event->property()->toString());
        self::assertSame('val', $event->value()->toString());
    }

    public function test_it_should_not_trigger_event_when_value_was_not_changed(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createText('text')->endProperty()
            ->getSchema();
        $record = RecordAggregate::withValues(
            RecordId::random(),
            $schema,
            [
                'text' => StringValue::fromString('val'),
            ]
        );
        $record->uncommitedEvents(); // reset

        $record->setValue('text', StringValue::fromString('val'));
        self::assertCount(0, $record->uncommitedEvents());
    }

    public function test_it_should_trigger_event_when_value_was_not_changed(): void
    {
        $schema = DocumentBuilder::createDocument()
            ->createText('text')->endProperty()
            ->getSchema();
        $record = RecordAggregate::withValues(
            RecordId::random(),
            $schema,
            [
                'text' => StringValue::fromString('before'),
            ]
        );
        $record->uncommitedEvents(); // reset

        $record->setValue('text', StringValue::fromString('after'));
        $events = $record->uncommitedEvents();
        self::assertCount(1, $events);
        /**
         * @var PropertyValueWasChanged $event
         */
        $event = $events[0];
        self::assertInstanceOf(PropertyValueWasChanged::class, $event);
        self::assertSame($record->getIdentity()->toString(), $event->recordId()->toString());
        self::assertSame($record->getDocumentId()->toString(), $event->documentId()->toString());
        self::assertSame('text', $event->property()->toString());
        self::assertSame('before', $event->fromValue()->toString());
        self::assertSame('after', $event->toValue()->toString());
    }
}
