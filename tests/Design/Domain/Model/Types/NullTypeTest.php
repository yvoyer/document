<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;

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
                true, 'The property "name" expected a "null" value, "true" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "null" value, "false" given.'
            ],
            "String value should be invalid" => [
                'invalid', 'The property "name" expected a "null" value, "invalid" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', 'The property "name" expected a "null" value, "12.34" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "null" value, "12.34" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "null" value, "34" given.'
            ],
            "Array should be invalid" => [
                [], 'The property "name" expected a "null" value, "[]" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "null" value, "stdClass" given.'
            ],
        ];
    }

    public function test_it_should_set_the_null_value()
    {
        $this->assertInstanceOf(
            EmptyValue::class,
            $value = $this->getType()->createValue('text', null)
        );
        $this->assertSame('', $value->toString());
    }
}
