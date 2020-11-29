<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\Parameters\ArrayParameter;
use Star\Component\Document\Design\Domain\Model\Parameters\DefaultValue;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Tests\Design\Domain\Model\Parameters\Stubs\ParameterAlwaysInError;

final class ArrayParameterTest extends TestCase
{
    public function test_it_should_create_from_data(): void
    {
        $original = new ArrayParameter(
            new NullParameter(),
            new NullParameter()
        );
        $data = $original->toParameterData();
        $this->assertInstanceOf(ArrayParameter::class, $new = $data->createParameter());
        $this->assertEquals($data, $new->toParameterData());
    }

    public function test_it_should_return_the_default_values(): void
    {
        $original = new ArrayParameter(
            new DefaultValue(StringValue::fromString('v1')),
            new DefaultValue(StringValue::fromString('v2'))
        );

        $this->assertSame('v1', $original->toWriteFormat(new EmptyValue())->toString());
    }

    public function test_it_should_validate_all_parameters(): void
    {
        $original = new ArrayParameter(
            new ParameterAlwaysInError('error 1'),
            new ParameterAlwaysInError('error 2')
        );

        $original->validate('prop', new EmptyValue(), $errors = new ErrorList());
        $this->assertTrue($errors->hasErrors());
        $this->assertSame(
            [
                'error 1',
                'error 2',
            ],
            $errors->getLocalizedMessages('en')
        );
    }
}
