<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\Parameters\DefaultValue;
use Star\Component\Document\Design\Domain\Model\Parameters\InvalidDefaultValue;

final class DefaultValueTest extends TestCase
{
    public function test_it_should_return_parameter_data(): void
    {
        $parameter = new DefaultValue(StringValue::fromString('default'));
        $data = $parameter->toParameterData();
        $new = $data->createParameter();
        $this->assertInstanceOf(DefaultValue::class, $new);
    }

    public function test_it_should_not_allow_empty_value_as_default_value(): void
    {
        $this->expectException(InvalidDefaultValue::class);
        $this->expectExceptionMessage('Value "empty()" is not a valid default value.');
        new DefaultValue(new EmptyValue());
    }
}
