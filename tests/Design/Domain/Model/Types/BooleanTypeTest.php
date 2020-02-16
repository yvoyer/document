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
use Star\Component\Document\Design\Domain\Model\Types\BooleanType;

final class BooleanTypeTest extends BaseTestType
{
    protected function getType(): PropertyType
    {
        return new BooleanType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "String value should be invalid" => [StringValue::fromString('invalid')],
            "String numeric should be invalid" => [FloatValue::fromString('12.34')],
            "Float should be invalid" => [FloatValue::fromFloat(12.34)],
            "Integer should be invalid" => [IntegerValue::fromInt(34)],
            "Array should be invalid" => [ArrayOfInteger::withValues(1)],
            "Object should be invalid" => [new ObjectValue((object) [])],
            "string 1 should be invalid" => [StringValue::fromString('1')],
            "string 0 should be invalid" => [StringValue::fromString('0')],
            "int 1 should be invalid" => [IntegerValue::fromInt(1)],
            "int 0 should be invalid" => [IntegerValue::fromInt(0)],
        ];
    }

    public static function provideInvalidTypesOfValueExceptions(): array
    {
        return [
            "String value should be invalid" => [StringValue::fromString('invalid')],
            "Float should be invalid" => [FloatValue::fromFloat(12.34)],
            "Integer should be invalid" => [IntegerValue::fromInt(34)],
            "Array should be invalid" => [ArrayOfInteger::withValues(1)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public function test_it_should_accept_empty_value(): void
    {
        $this->assertTrue($this->getType()->supportsValue(new EmptyValue()));
    }

    public function test_it_should_accept_true()
    {
        $this->assertTrue($this->getType()->supportsValue(BooleanValue::trueValue()));
    }

    public function test_it_should_accept_false()
    {
        $this->assertTrue($this->getType()->supportsValue(BooleanValue::falseValue()));
    }

    public function test_it_should_accept_string_true()
    {
        $this->assertTrue($this->getType()->supportsValue(BooleanValue::fromString('true')));
    }

    public function test_it_should_accept_string_false()
    {
        $this->assertTrue($this->getType()->supportsValue(BooleanValue::fromString('false')));
    }
}
