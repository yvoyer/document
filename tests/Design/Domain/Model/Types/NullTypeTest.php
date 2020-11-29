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
use Star\Component\Document\Design\Domain\Model\Types\NullType;

final class NullTypeTest extends BaseTestType
{
    /**
     * @return PropertyType
     */
    protected function getType(): PropertyType
    {
        return new NullType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [BooleanValue::trueValue()],
            "Boolean false should be invalid" => [BooleanValue::falseValue()],
            "String value should be invalid" => [StringValue::fromString('invalid')],
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
            "String value should be invalid" => [StringValue::fromString('invalid')],
            "Float should be invalid" => [FloatValue::fromFloat(12.34)],
            "Integer should be invalid" => [IntegerValue::fromInt(34)],
            "Array should be invalid" => [ArrayOfInteger::withValues(1)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public function test_it_should_allow_the_null_value()
    {
        $this->assertTrue($this->getType()->supportsValue(new EmptyValue()));
    }

    public function test_it_should_allow_the_empty_string()
    {
        $this->assertTrue($this->getType()->supportsValue(StringValue::fromString('')));
    }
}
