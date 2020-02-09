<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\OptionListValue;

final class CustomListTypeTest extends BaseTestType
{
    protected function getType(): PropertyType
    {
        return new CustomListType(OptionListValue::withElements(3));
    }

    public static function provideInvalidValuesExceptions(): array
    {
        $arrayItemMessage = 'The property "name" only accepts an array made of the following values: "1;2;3", ';
        $typeMessage = 'The property "name" expected a "list" value, ';

        return [
            "Boolean true should be invalid" => [
                true, $typeMessage . '"boolean(true)" given.'
            ],
            "Boolean false should be invalid" => [
                false, $typeMessage . '"boolean(false)" given.'
            ],
            "String value should be invalid" => [
                'invalid', $typeMessage . '"string(invalid)" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', $typeMessage . '"float(12.34)" given.'
            ],
            "Float should be invalid" => [
                12.34, $typeMessage . '"float(12.34)" given.'
            ],
            "Integer should be invalid" => [
                34, $typeMessage . '"int(34)" given.'
            ],
            "Option string do not exists in available options" => [
                ['invalid'], $arrayItemMessage . '"list(["invalid"])" given.'
            ],
            "Option int do not exists in available options" => [
                [123], $arrayItemMessage . '"list([123])" given.'
            ],
            "Array of invalid option boolean" => [
                [true, false], $arrayItemMessage . '"list([true,false])" given.'
            ],
            "Array of invalid option array" => [
                [[]], $arrayItemMessage . '"list([[]])" given.'
            ],
            "Array of invalid option object" => [
                [(object) []], $arrayItemMessage . '"list([{}])" given.'
            ],
            "Array of invalid option null" => [
                [null], $arrayItemMessage . '"list([])" given.'
            ],
            "Object should be invalid" => [
                (object) [], $arrayItemMessage . '"object(stdClass)" given.'
            ],
            "null should be invalid" => [
                null, $typeMessage . '"empty()" given.'
            ],
            "invalid second id should be invalid" => [
                [1, 999], $arrayItemMessage . '"[1,999]" given.'
            ],
        ];
    }

    public function test_it_should_accept_empty_array()
    {
        $this->assertSame(
            '',
            $value = $this->getType()->createValue('prop', RawValue::fromMixed([]))->toString()
        );
    }

    public function test_it_should_accept_single_value_array()
    {
        $this->assertInstanceOf(
            OptionListValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed([1]))
        );
        $this->assertSame('1', $value->toString());
        $this->assertSame('[Label 1]', $value->getType());
    }

    public function test_it_should_accept_multi_value_array()
    {
        $this->assertInstanceOf(
            OptionListValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed([1, 3]))
        );
        $this->assertSame('1;3', $value->toString());
        $this->assertSame('[Label 1;Label 3]', $value->getType());
    }

    public function test_it_should_return_in_same_order_as_given()
    {
        $this->assertInstanceOf(
            OptionListValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed([2, 1, 3]))
        );
        $this->assertSame('2;1;3', $value->toString());
        $this->assertSame('[Label 2;Label 1;Label 3]', $value->getType());
    }

    public function test_it_should_accept_string_value_for_key()
    {
        $this->assertInstanceOf(
            OptionListValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed(["1", "2", "3"]))
        );
        $this->assertSame('1;2;3', $value->toString());
        $this->assertSame('[Label 1;Label 2;Label 3]', $value->getType());
    }

    public function test_it_should_accept_imploded_string()
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('prop', RawValue::fromMixed(""))->toString()
        );

        $this->assertInstanceOf(
            OptionListValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed("2"))
        );
        $this->assertSame('2', $value->toString());
        $this->assertSame('[Label 2]', $value->getType());

        $this->assertInstanceOf(
            OptionListValue::class,
            $value = $this->getType()->createValue('prop', RawValue::fromMixed("1;2;3"))
        );
        $this->assertSame('1;2;3', $value->toString());
        $this->assertSame('[Label 1;Label 2;Label 3]', $value->getType());
    }
}
