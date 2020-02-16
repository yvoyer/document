<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\ObjectValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\StringType;

final class StringTypeTest extends BaseTestType
{
    protected function getType(): PropertyType
    {
        return new StringType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [BooleanValue::trueValue()],
            "Boolean false should be invalid" => [BooleanValue::falseValue()],
            "Array should be invalid" => [ArrayOfInteger::withValues(12)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public static function provideInvalidTypesOfValueExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [BooleanValue::trueValue()],
            "Array should be invalid" => [ArrayOfInteger::withValues(12)],
            "Object should be invalid" => [new ObjectValue((object) [])],
        ];
    }

    public function test_it_should_set_the_text_value(): void
    {
        $this->assertTrue($this->getType()->supportsValue(StringValue::fromString('Some value')));
    }

    public function test_it_should_allow_empty_value(): void
    {
        $this->assertTrue($this->getType()->supportsValue(StringValue::fromString('')));
    }

    public function test_it_should_allow_int_value(): void
    {
        $this->assertTrue($this->getType()->supportsValue(StringValue::fromInt(123)));
    }

    public function test_it_should_allow_float_value(): void
    {
        $this->assertTrue($this->getType()->supportsValue(StringValue::fromFloat(12.34)));
    }
}
