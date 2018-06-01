<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Exception\EmptyAllowedOptions;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\ListValue;

final class CustomListTypeTest extends TypeTest
{
    /**
     * @return PropertyType
     */
    protected function getType(): PropertyType
    {
        return new CustomListType([0 => 'value 1', 1 => 'value 2', 2 => 'value 3']);
    }

    public static function provideInvalidValuesExceptions(): array
    {
        $message = 'The property "name" only accepts an array made of the following values: "0;1;2", ';
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
            $value = $this->getType()->createValue('prop', [0])
        );
        $this->assertSame('value 1', $value->toString());
    }

    public function test_it_should_accept_multi_value_array()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', [0, 2])
        );
        $this->assertSame('value 1;value 3', $value->toString());
    }

    public function test_it_should_return_in_same_order_as_given()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', [1, 0, 2])
        );
        $this->assertSame('value 2;value 1;value 3', $value->toString());
    }

    public function test_it_should_accept_string_value_for_key()
    {
        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', ["0", "1", "2"])
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
            $value = $this->getType()->createValue('prop', "1")
        );
        $this->assertSame('value 2', $value->toString());

        $this->assertInstanceOf(
            ListValue::class,
            $value = $this->getType()->createValue('prop', "0;1;2")
        );
        $this->assertSame('value 1;value 2;value 3', $value->toString());
    }

    public function test_it_should_throw_exception_when_configuring_option_list_with_no_values()
    {
        $this->expectException(EmptyAllowedOptions::class);
        $this->expectExceptionMessage('Custom list properties must receive at least one option, none given.');
        new CustomListType([]);
    }
}
