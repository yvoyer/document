<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\FloatValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\ListOfOptionsType;

final class ListOfOptionsTypeTest extends BaseTestType
{
    protected function getType(): PropertyType
    {
        return new ListOfOptionsType('list', OptionListValue::withElements(3));
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [BooleanValue::trueValue()],
            "Boolean false should be invalid" => [BooleanValue::falseValue()],
            "String value should be invalid" => [StringValue::fromString('invalid')],
            "String numeric should be invalid" => [FloatValue::fromString('12.34')],
            "Float should be invalid" => [FloatValue::fromFloat(12.34)],
            "Integer should be invalid" => [IntegerValue::fromInt(34)],
            "Option int do not exists in available options" => [ArrayOfInteger::withValues(123)],
            "invalid second id should be invalid" => [ArrayOfInteger::withValues(1, 999)],
        ];
    }

    public static function provideInvalidTypesOfValueExceptions(): array
    {
        return [
            "Boolean should be invalid" => [BooleanValue::falseValue()],
            "String value should be invalid" => [StringValue::fromString('invalid')],
            "String numeric should be invalid" => [FloatValue::fromString('12.34')],
            "Integer should be invalid" => [IntegerValue::fromInt(34)],
        ];
    }

    public function test_it_should_not_allow_value_not_allowed(): void
    {
        $this->assertFalse($this->getType()->supportsValue(ArrayOfInteger::withValues(123)));
    }

    public function test_it_should_accept_empty_array()
    {
        $this->assertTrue($this->getType()->supportsValue(new EmptyValue()));
    }

    public function test_it_should_accept_single_value_array()
    {
        $this->assertTrue($this->getType()->supportsValue(ArrayOfInteger::withValues(1)));
    }

    public function test_it_should_accept_multi_value_array()
    {
        $this->assertTrue($this->getType()->supportsValue(ArrayOfInteger::withValues(1, 3)));
    }

    public function test_it_should_return_in_same_order_as_given()
    {
        $value = ArrayOfInteger::withValues(2, 1, 3);
        $this->assertSame('2;1;3', $value->toString());
        $this->assertSame(
            'options(Label 2;Label 1;Label 3)',
            $this->getType()->toReadFormat($value)->toTypedString()
        );
    }

    public function test_it_should_accept_string_value_for_key()
    {
        $this->assertSame(
            'options(Label 1;Label 2;Label 3)',
            $this->getType()->toReadFormat(ArrayOfInteger::withValues(1, 2, 3))->toTypedString()
        );
    }

    public function test_it_should_accept_imploded_string()
    {
        $this->assertSame(
            'options(Label 2)',
            $this->getType()->toReadFormat(IntegerValue::fromInt(2))->toTypedString()
        );
        $this->assertSame(
            'options(Label 1;Label 2;Label 3)',
            $this->getType()->toReadFormat(StringValue::fromString("1;2;3"))->toTypedString()
        );
    }

    public function test_supports_type(): void
    {
        self::assertTrue($this->getType()->supportsValue(ArrayOfInteger::withValues(1, 2, 3)));
    }

    public function test_it_should_convert_empty_array_when_empty_value(): void
    {
        self::assertSame('empty()', $this->getType()->toReadFormat(new EmptyValue())->toTypedString());
    }

    public function test_it_should_convert_array_of_id_when_not_empty(): void
    {
        self::assertSame(
            'options(Label 1;Label 3)',
            $this->getType()->toReadFormat(ArrayOfInteger::withValues(1, 3))->toTypedString()
        );
    }
}
