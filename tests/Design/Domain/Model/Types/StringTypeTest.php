<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class StringTypeTest extends TypeTest
{
    public function getType(): PropertyType
    {
        return new StringType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [
                true, 'The property "name" expected a "string" value, "boolean" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "string" value, "boolean" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "string" value, "double" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "string" value, "integer" given.'
            ],
            "Array should be invalid" => [
                [], 'The property "name" expected a "string" value, "array" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "string" value, "object" given.'
            ],
            "null should be invalid" => [
                null, 'The property "name" expected a "string" value, "NULL" given.'
            ],
        ];
    }

    public function test_it_should_set_the_text_value()
    {
        $this->assertInstanceOf(
            StringValue::class,
            $value = $this->getType()->createValue('text', 'Some value')
        );
        $this->assertSame('Some value', $value->toString());
    }

    public function test_it_should_empty_value()
    {
        $this->assertInstanceOf(
            StringValue::class,
            $value = $this->getType()->createValue('text', '')
        );
        $this->assertSame('', $value->toString());
    }
}
