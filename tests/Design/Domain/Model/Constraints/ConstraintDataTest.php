<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Constraints;

use InvalidArgumentException;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Constraints\NoConstraint;

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
}
