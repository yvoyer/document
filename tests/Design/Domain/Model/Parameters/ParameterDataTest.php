<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Parameters;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class ParameterDataTest extends TestCase
{
    public function test_it_should_throw_exception_when_no_class_index_set(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Class index is not defined to a "PropertyParameter" subclass.');
        ParameterData::fromArray([]);
    }

    public function test_it_should_throw_exception_when_class_argument_not_subclass_of_parameter(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Class "dsaas" was expected to be subclass of "' . PropertyParameter::class . '".'
        );
        ParameterData::fromArray(['class' => 'dsaas', 'arguments' => []]);
    }

    public function test_it_should_throw_exception_when_no_arguments_index_set(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument list is not defined to an array.');
        ParameterData::fromArray(['class' => 'class']);
    }

    public function test_it_should_throw_exception_when_argument_not_found(): void
    {
        $data = ParameterData::fromArray(['class' => NullParameter::class, 'arguments' => []]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument "invalid" could not be found.');
        $data->getArgument('invalid');
    }

    public function test_it_should_be_created_from_array(): void
    {
        $data = ParameterData::fromArray(
            [
                'class' => NullParameter::class,
                'arguments' => ['arg' => 'val'],
            ]
        );
        $this->assertSame('val', $data->getArgument('arg'));
    }
}
