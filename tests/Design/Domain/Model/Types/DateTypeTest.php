<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\FloatValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\ObjectValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\DateType;

final class DateTypeTest extends BaseTestType
{
    protected function getType(): PropertyType
    {
        return new DateType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [BooleanValue::trueValue()],
            "Boolean false should be invalid" => [BooleanValue::falseValue()],
            "String format is not valid" => [StringValue::fromString('invalid')],
            "String numeric should be invalid" => [StringValue::fromString('12.34')],
            "Float should be invalid" => [FloatValue::fromFloat(12.34)],
            "Integer should be invalid" => [IntegerValue::fromInt(34)],
            "Array should be invalid" => [ArrayOfInteger::withValues(1)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public static function provideInvalidTypesOfValueExceptions(): array
    {
        return [
            "Boolean should be invalid" => [BooleanValue::falseValue()],
            "String format is not valid" => [StringValue::fromString('invalid')],
            "Float should be invalid" => [FloatValue::fromFloat(12.34)],
            "Integer should be invalid" => [IntegerValue::fromInt(34)],
            "Array should be invalid" => [ArrayOfInteger::withValues(1)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public function test_it_should_set_as_text_value()
    {
        $this->assertTrue(
            $this->getType()->supportsValue(DateValue::fromString('2001-01-01'))
        );
    }

    public function test_it_should_set_as_date_time_value()
    {
        $this->assertTrue(
            $this->getType()->supportsValue(
                DateValue::fromDateTime(new \DateTimeImmutable('2002-02-05 12:34:56'))
            )
        );
    }

    public function test_it_should_allow_empty_string(): void
    {
        $this->assertTrue($this->getType()->supportsValue(new EmptyValue()));
    }

    public function test_it_should_allow_partial_string_date(): void
    {
        $this->assertTrue($this->getType()->supportsValue(DateValue::fromString('Feb', 'M')));
    }
}
