<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\NullValue;

final class NullTypeTest extends TypeTest
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
                true, 'The property "name" expected a "null" value, "boolean" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "null" value, "boolean" given.'
            ],
            "String value should be invalid" => [
                'invalid', 'The property "name" expected a "null" value, "string" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', 'The property "name" expected a "null" value, "string" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "null" value, "double" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "null" value, "integer" given.'
            ],
            "Array should be invalid" => [
                [], 'The property "name" expected a "null" value, "array" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "null" value, "object" given.'
            ],
        ];
    }

    public function test_it_should_set_the_null_value()
    {
        $this->assertInstanceOf(
            NullValue::class,
            $value = $this->getType()->createValue('text', null)
        );
        $this->assertSame('null', $value->toString());
    }
}
