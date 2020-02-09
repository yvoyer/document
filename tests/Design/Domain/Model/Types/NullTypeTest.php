<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

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
            "Boolean true should be invalid" => [
                true, 'The property "name" expected a "null" value, "boolean(true)" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "null" value, "boolean(false)" given.'
            ],
            "String value should be invalid" => [
                'invalid', 'The property "name" expected a "null" value, "string(invalid)" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', 'The property "name" expected a "null" value, "float(12.34)" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "null" value, "float(12.34)" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "null" value, "int(34)" given.'
            ],
            "Array should be invalid" => [
                [1], 'The property "name" expected a "null" value, "list([1])" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "null" value, "object(stdClass)" given.'
            ],
        ];
    }

    public function test_it_should_allow_the_null_value()
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('name', RawValue::fromMixed(null))->toString()
        );
    }

    public function test_it_should_allow_the_empty_string()
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('name', RawValue::fromMixed(''))->toString()
        );
    }

    public function test_it_should_allow_empty_array()
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('name', RawValue::fromMixed([]))->toString()
        );
    }
}
