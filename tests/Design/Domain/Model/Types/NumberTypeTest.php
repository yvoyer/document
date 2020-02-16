<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\FloatValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\ObjectValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\NumberType;

final class NumberTypeTest extends BaseTestType
{
    public function getType(): PropertyType
    {
        return new NumberType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [BooleanValue::trueValue()],
            "Boolean false should be invalid" => [BooleanValue::falseValue()],
            "String value should be invalid" => [StringValue::fromString('invalid')],
            "Array should be invalid" => [ArrayOfInteger::withValues(1)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public static function provideInvalidTypesOfValueExceptions(): array
    {
        return [
            "Boolean should be invalid" => [BooleanValue::falseValue()],
            "String value should be invalid" => [StringValue::fromString('invalid')],
            "Array should be invalid" => [ArrayOfInteger::withValues(1)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public function test_it_should_allow_empty_value(): void
    {
        $this->assertTrue($this->getType()->supportsValue(new EmptyValue()));
    }

    public function test_it_should_allow_int_value()
    {
        $this->assertTrue($this->getType()->supportsValue(IntegerValue::fromInt(123)));
    }

    public function test_it_should_allow_int_as_string_value()
    {
        $this->assertTrue($this->getType()->supportsValue(IntegerValue::fromString('123')));
    }

    public function test_it_should_allow_float_value()
    {
        $this->assertTrue($this->getType()->supportsValue(FloatValue::fromFloat(123.456)));
    }

    public function test_it_should_allow_float_as_string_value()
    {
        $this->assertTrue($this->getType()->supportsValue(FloatValue::fromString('123.45')));
    }
}
