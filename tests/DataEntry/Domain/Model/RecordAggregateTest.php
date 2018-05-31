<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Exception\UndefinedProperty;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;

final class RecordAggregateTest extends TestCase
{
    public function test_it_should_set_a_property_value()
    {
        $record = new RecordAggregate(new RecordId('id'), new AlwaysCreateValue(new StringValue('value')));
        $record->setValue('name', 'ignored value');
        $this->assertInstanceOf(PropertyValue::class, $value = $record->getValue('name'));
        $this->assertSame('value', $value->toString());
    }

    public function test_it_should_throw_exception_when_property_never_set()
    {
        $record = new RecordAggregate(new RecordId('id'), new AlwaysCreateValue(new StringValue('value')));

        $this->expectException(UndefinedProperty::class);
        $this->expectExceptionMessage('Property with name "name" is not defined on record.');
        $record->getValue('name');
    }
}
