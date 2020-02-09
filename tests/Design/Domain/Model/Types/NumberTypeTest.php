<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Values\IntegerValue;

final class NumberTypeTest extends BaseTestType
{
    public function getType(): PropertyType
    {
        return new NumberType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [
                true, 'The property "name" expected a "number" value, "boolean(true)" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "number" value, "boolean(false)" given.'
            ],
            "String value should be invalid" => [
                'invalid', 'The property "name" expected a "number" value, "string(invalid)" given.'
            ],
            "Array should be invalid" => [
                [1], 'The property "name" expected a "number" value, "list([1])" given.'
            ],
            "Empty array should be invalid" => [
                [], 'The property "name" expected a "number" value, "empty()" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "number" value, "object(stdClass)" given.'
            ],
            "null should be invalid" => [
                null, 'The property "name" expected a "number" value, "empty()" given.'
            ],
        ];
    }

    public function test_it_should_allow_int_value()
    {
        $this->assertInstanceOf(
            IntegerValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed(123))
        );
        $this->assertSame('123', $value->toString());
    }

    public function test_it_should_allow_int_as_string_value()
    {
        $this->assertInstanceOf(
            IntegerValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed('123'))
        );
        $this->assertSame('123', $value->toString());
    }

    public function test_it_should_allow_float_value()
    {
        $this->assertInstanceOf(
            FloatValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed(123.456))
        );
        $this->assertSame('123.456', $value->toString());
    }

    public function test_it_should_allow_float_as_string_value()
    {
        $this->assertInstanceOf(
            FloatValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed('123.45'))
        );
        $this->assertSame('123.45', $value->toString());
    }
}
