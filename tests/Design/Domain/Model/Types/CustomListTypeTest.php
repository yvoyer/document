<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\Design\Domain\Model\Values\ListValue;

final class CustomListTypeTest extends TypeTest
{
    protected function getType(): PropertyType
    {
        return new CustomListType(
            ListOptionValue::withValueAsLabel(1, 'value 1'),
            ListOptionValue::withValueAsLabel(2, 'value 2'),
            ListOptionValue::withValueAsLabel(3, 'value 3')
        );
    }

    public static function provideInvalidValuesExceptions(): array
    {
        $message = 'The property "name" only accepts an array made of the following values: "1;2;3", ';
        return [
            "Boolean true should be invalid" => [
                true, $message . '"true" given.'
            ],
            "Boolean false should be invalid" => [
                false, $message . '"false" given.'
            ],
            "String value should be invalid" => [
                'invalid', $message . '"invalid" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', $message . '"12.34" given.'
            ],
            "Float should be invalid" => [
                12.34, $message . '"12.34" given.'
            ],
            "Integer should be invalid" => [
                34, $message . '"34" given.'
            ],
            "Option string do not exists in available options" => [
                ['invalid'], $message . '"["invalid"]" given.'
            ],
            "Option int do not exists in available options" => [
                [123], $message . '"[123]" given.'
            ],
            "Array of invalid option boolean" => [
                [true, false], $message . '"[true,false]" given.'
            ],
            "Array of invalid option array" => [
                [[]], $message . '"[[]]" given.'
            ],
            "Array of invalid option object" => [
                [(object) []], $message . '"[{}]" given.'
            ],
            "Array of invalid option null" => [
                [null], $message . '"[null]" given.'
            ],
            "Object should be invalid" => [
                (object) [], $message . '"stdClass" given.'
            ],
            "null should be invalid" => [
                null, $message . '"NULL" given.'
            ],
            "invalid second id should be invalid" => [
                [1, 999], $message . '"[1,999]" given.'
            ],
        ];
    }

    public function test_it_should_accept_empty_array()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', [])
        );
        $this->assertSame('', $value->toString());
    }

    public function test_it_should_accept_single_value_array()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', [1])
        );
        $this->assertSame('value 1', $value->toString());
    }

    public function test_it_should_accept_multi_value_array()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', [1, 3])
        );
        $this->assertSame('value 1;value 3', $value->toString());
    }

    public function test_it_should_return_in_same_order_as_given()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', [2, 1, 3])
        );
        $this->assertSame('value 2;value 1;value 3', $value->toString());
    }

    public function test_it_should_accept_string_value_for_key()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', ["1", "2", "3"])
        );
        $this->assertSame('value 1;value 2;value 3', $value->toString());
    }

    public function test_it_should_accept_imploded_string()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', "")
        );
        $this->assertSame('', $value->toString());

        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', "2")
        );
        $this->assertSame('value 2', $value->toString());

        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', "1;2;3")
        );
        $this->assertSame('value 1;value 2;value 3', $value->toString());
    }
}
