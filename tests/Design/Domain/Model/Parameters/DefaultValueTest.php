<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;

final class DefaultValueTest extends TestCase
{
    public function test_it_should_return_parameter_data(): void
    {
        $parameter = new DefaultValue(new EmptyValue());
        $data = $parameter->toParameterData();
        $new = $data->createParameter();
        $this->assertInstanceOf(DefaultValue::class, $new);
        $this->assertSame('default-value', $new->getName());
    }
}
