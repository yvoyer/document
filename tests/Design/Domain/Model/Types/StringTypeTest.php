<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class StringTypeTest extends BaseTestType
{
    public function getType(): PropertyType
    {
        return new StringType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [
                true, 'The property "name" expected a "string" value, "boolean(true)" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "string" value, "boolean(false)" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "string" value, "float(12.34)" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "string" value, "number(34)" given.'
            ],
            "Array should be invalid" => [
                [12], 'The property "name" expected a "string" value, "list([12])" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "string" value, "object(stdClass)" given.'
            ],
        ];
    }

    public function test_it_should_set_the_text_value(): void
    {
        $this->assertInstanceOf(
            StringValue::class,
            $value = $this->getType()->createValue('text', RawValue::fromMixed('Some value'))
        );
        $this->assertSame('Some value', $value->toString());
    }

    public function test_it_should_allow_empty_value(): void
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('text', RawValue::fromMixed(''))->toString()
        );
    }
}
