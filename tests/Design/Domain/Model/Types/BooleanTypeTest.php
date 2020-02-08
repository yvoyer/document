<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\BooleanValue;

final class BooleanTypeTest extends BaseTestType
{
    protected function getType(): PropertyType
    {
        return new BooleanType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "String value should be invalid" => [
                'invalid', 'The property "name" expected a "boolean" value, "invalid" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', 'The property "name" expected a "boolean" value, "12.34" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "boolean" value, "12.34" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "boolean" value, "34" given.'
            ],
            "Array should be invalid" => [
                [], 'The property "name" expected a "boolean" value, "[]" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "boolean" value, "stdClass" given.'
            ],
            "null should be invalid" => [
                null, 'The property "name" expected a "boolean" value, "NULL" given.'
            ],
        ];
    }

    public function test_it_should_accept_true()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', true)
        );
        $this->assertSame('true', $value->toString());
    }

    public function test_it_should_accept_false()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', false)
        );
        $this->assertSame('false', $value->toString());
    }

    public function test_it_should_accept_string_true()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', 'true')
        );
        $this->assertSame('true', $value->toString());
    }

    public function test_it_should_accept_string_false()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', 'false')
        );
        $this->assertSame('false', $value->toString());
    }

    public function test_it_should_accept_int_one()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', 1)
        );
        $this->assertSame('true', $value->toString());
    }

    public function test_it_should_accept_int_zero()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', 0)
        );
        $this->assertSame('false', $value->toString());
    }

    public function test_it_should_accept_string_one()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', '1')
        );
        $this->assertSame('true', $value->toString());
    }

    public function test_it_should_accept_string_zero()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('text', '0')
        );
        $this->assertSame('false', $value->toString());
    }
}
