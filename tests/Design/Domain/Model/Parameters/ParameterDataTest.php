<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Parameters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Parameters\NullParameter;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;

final class ParameterDataTest extends TestCase
{
    public function test_it_should_throw_exception_when_no_class_index_set(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class index is not defined to a "PropertyParameter" subclass.');
        ParameterData::fromArray([]);
    }

    public function test_it_should_throw_exception_when_class_argument_not_subclass_of_parameter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Class "dsaas" was expected to be subclass of "' . PropertyParameter::class . '".'
        );
        ParameterData::fromArray(['class' => 'dsaas', 'arguments' => []]);
    }

    public function test_it_should_throw_exception_when_no_arguments_index_set(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument list is not defined to an array.');
        ParameterData::fromArray(['class' => 'class']);
    }

    public function test_it_should_throw_exception_when_argument_not_found(): void
    {
        $data = ParameterData::fromArray(['class' => NullParameter::class, 'arguments' => []]);

        $this->expectException(InvalidArgumentException::class);
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

    public function test_it_should_be_created_from_json_string(): void
    {
        $data = ParameterData::fromJson(NullParameter::class, '[]');
        self::assertSame(
            [
                'class' => NullParameter::class,
                'arguments' => [],
            ],
            $data->toArray()
        );
        self::assertInstanceOf(NullParameter::class, $data->createParameter());

        $data = ParameterData::fromJson(
            StubParameterWithRequirement::class,
            '{"id":12,"name":"name"}'
        );
        self::assertSame(
            [
                'class' => StubParameterWithRequirement::class,
                'arguments' => [
                    'id' => 12,
                    'name' => 'name',
                ],
            ],
            $data->toArray()
        );
        self::assertInstanceOf(StubParameterWithRequirement::class, $data->createParameter());
    }
}

final class StubParameterWithRequirement implements PropertyParameter
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toParameterData(): ParameterData
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public static function fromParameterData(ParameterData $data): PropertyParameter
    {
        return new self($data->getArgument('id'), $data->getArgument('name'));
    }
}
