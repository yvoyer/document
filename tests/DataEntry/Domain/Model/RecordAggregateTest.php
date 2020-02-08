<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Exception\UndefinedProperty;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
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

        $this->expectException(UndefinedProperty::class);
        $this->expectExceptionMessage('Property with name "name" is not defined on record.');
        $record->getValue('name');
    }
}
