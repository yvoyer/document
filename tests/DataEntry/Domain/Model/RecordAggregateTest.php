<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Exception\UndefinedProperty;
use Star\Component\Document\DataEntry\Domain\Model\Validation\StrategyToHandleValidationErrors;
use Star\Component\Document\DataEntry\Domain\Stub\StubSchema;

final class RecordAggregateTest extends TestCase
{
    public function test_it_should_set_a_property_value(): void
    {
        $property = 'name';
        $schema = StubSchema::allOptional();

        $record = new RecordAggregate(new RecordId('id'), $schema);
        $record->setValue(
            $property,
            'ignored value',
            $this->createMock(StrategyToHandleValidationErrors::class)
        );
        $this->assertInstanceOf(RecordValue::class, $value = $record->getValue($property));
    }

    public function test_it_should_throw_exception_when_property_never_set(): void
    {
        $property = 'name';
        $schema = StubSchema::allOptional();

        $record = new RecordAggregate(new RecordId('id'), $schema);

        $this->expectException(UndefinedProperty::class);
        $this->expectExceptionMessage('Property with name "name" is not defined on record.');
        $record->getValue($property);
    }
}
