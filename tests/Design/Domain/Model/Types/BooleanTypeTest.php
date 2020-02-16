<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
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
                'invalid', 'The property "name" expected a "boolean" value, "string(invalid)" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', 'The property "name" expected a "boolean" value, "float(12.34)" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "boolean" value, "float(12.34)" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "boolean" value, "int(34)" given.'
            ],
            "Array should be invalid" => [
                [1], 'The property "name" expected a "boolean" value, "list([1])" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "boolean" value, "object(stdClass)" given.'
            ],
            "string 1 should be invalid" => [
                '1', 'The property "name" expected a "boolean" value, "int(1)" given.'
            ],
            "string 0 should be invalid" => [
                '0', 'The property "name" expected a "boolean" value, "int(0)" given.'
            ],
            "int 1 should be invalid" => [
                1, 'The property "name" expected a "boolean" value, "int(1)" given.'
            ],
            "int 0 should be invalid" => [
                0, 'The property "name" expected a "boolean" value, "int(0)" given.'
            ],
        ];
    }

    public function test_it_should_accept_empty_value(): void
    {
        $this->assertTrue($this->getType()->createValue('name', RawValue::fromEmpty())->isEmpty());
    }

    public function test_it_should_accept_true()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('name', RawValue::fromBoolean(true))
        );
        $this->assertSame('true', $value->toString());
    }

    public function test_it_should_accept_false()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('name', RawValue::fromBoolean(false))
        );
        $this->assertSame('false', $value->toString());
    }

    public function test_it_should_accept_string_true()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('name', RawValue::fromString('true'))
        );
        $this->assertSame('true', $value->toString());
    }

    public function test_it_should_accept_string_false()
    {
        $this->assertInstanceOf(
            BooleanValue::class,
            $value = $this->getType()->createValue('name', RawValue::fromString('false'))
        );
        $this->assertSame('false', $value->toString());
    }

    public function test_it_should_return_default_value_representation(): void
    {
        $this->assertSame('boolean(false)', $this->getType()->createDefaultValue()->toTypedString());
    }
}
