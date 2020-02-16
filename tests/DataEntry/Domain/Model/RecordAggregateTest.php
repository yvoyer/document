<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ValidationFailedForProperty;
use Star\Component\Document\Design\Domain\Model\Schema\ReferencePropertyNotFound;
use Star\Component\Document\Design\Domain\Model\Schema\SchemaBuilder;

final class RecordAggregateTest extends TestCase
{
    public function test_it_should_set_a_property_value(): void
    {
        $record = RecordAggregate::withoutValues(
            RecordId::fromString('id'),
            SchemaBuilder::create()
                ->addText($property = 'name')->endProperty()
                ->getSchema()
        );
        $record->setValue(
            $property,
            'ignored value',
            $this->createMock(StrategyToHandleValidationErrors::class)
        );
        $this->assertSame('ignored value', $record->getValue($property)->toString());
    }

    public function test_it_should_throw_exception_when_property_never_set(): void
    {
        $record = RecordAggregate::withoutValues(
            RecordId::fromString('id'),
            SchemaBuilder::create()->getSchema()
        );

        $this->expectException(ReferencePropertyNotFound::class);
        $this->expectExceptionMessage('The property with name "name" could not be found.');
        $record->getValue('name');
    }

    public function test_it_should_throw_exception_when_setting_a_value_do_not_respect_constraint(): void
    {
        $schema = SchemaBuilder::create()
            ->addText('text')->required()->endProperty()
            ->getSchema();

        $this->expectException(ValidationFailedForProperty::class);
        $this->expectExceptionMessage(
            'Validation error: [Property named "text" is required, but "empty()" given.]'
        );
        RecordAggregate::withValues(
            RecordId::random(),
            $schema,
            [
                'text' => '',
            ]
        );
    }

    public function test_it_should_return_empty_value_when_not_set(): void
    {
        $schema = SchemaBuilder::create()
            ->addText('optional')->endProperty()
            ->getSchema();

        $record = RecordAggregate::withoutValues(RecordId::random(), $schema);
        $this->assertSame('', $record->getValue('optional')->toString());
        $this->assertTrue($record->getValue('optional')->isEmpty());
    }
}
