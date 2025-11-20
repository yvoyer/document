<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Constraints;

use InvalidArgumentException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class ConstraintDataTest extends TestCase
{
    public function test_it_should_throw_exception_when_not_json(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value "invalid" is not a valid JSON string.');
        ConstraintData::fromString('invalid');
    }

    public function test_it_should_throw_exception_when_missing_class_parameter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Constraint data does not contain an element with key "class".');
        ConstraintData::fromString('{"arguments":[]}');
    }

    public function test_it_should_throw_exception_when_missing_arguments_parameter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Constraint data does not contain an element with key "arguments".');
        ConstraintData::fromString('{"class":""}');
    }

    public function test_should_be_built_from_string(): void
    {
        $original = (new NoConstraint())->toData()->toString();
        self::assertSame($original, ConstraintData::fromString($original)->toString());
    }

    public function test_it_should_return_the_string_argument(): void
    {
        self::assertSame(
            'expected',
            ConstraintData::fromClass(ConstraintStub::class, ['value' => 'expected'])
                ->getStringArgument('value')
        );
    }

    public function test_it_should_return_the_integer_argument(): void
    {
        self::assertSame(
            42,
            ConstraintData::fromClass(ConstraintStub::class, ['value' => 42])
                ->getIntegerArgument('value')
        );
    }

    public function test_it_should_return_the_array_argument(): void
    {
        self::assertSame(
            ['expected'],
            ConstraintData::fromClass(ConstraintStub::class, ['value' => ['expected']])
                ->getArrayArgument('value')
        );
    }
}

final class ConstraintStub implements PropertyConstraint
{
    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toData(): ConstraintData
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
