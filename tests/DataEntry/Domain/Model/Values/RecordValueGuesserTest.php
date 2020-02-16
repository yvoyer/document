<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\FloatValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\RecordValueGuesser;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;

final class RecordValueGuesserTest extends TestCase
{
    public function test_it_should_throw_exception_when_type_cannot_be_guess(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Type of value "notmapped" is not mapped to a record value class.');
        RecordValueGuesser::guessValue('notmapped()');
    }

    public function test_it_should_throw_exception_when_invalid_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Provided value "invalid" is not of valid format, should be of format "type(value)".'
        );
        RecordValueGuesser::guessValue('invalid');
    }

    public function test_it_should_create_string(): void
    {
        self::assertInstanceOf(
            StringValue::class,
            $value = RecordValueGuesser::guessValue('string(something)')
        );
        self::assertSame('something', $value->toString());
    }

    public function test_it_should_create_date(): void
    {
        self::assertInstanceOf(
            DateValue::class,
            $value = RecordValueGuesser::guessValue('date(2000-10-10)')
        );
        self::assertSame('2000-10-10', $value->toString());
    }

    public function test_it_should_create_array_of_int(): void
    {
        self::assertInstanceOf(
            ArrayOfInteger::class,
            $value = RecordValueGuesser::guessValue('array(1;2;3)')
        );
        self::assertSame('1;2;3', $value->toString());
    }

    public function test_it_should_create_integer(): void
    {
        self::assertInstanceOf(
            IntegerValue::class,
            $value = RecordValueGuesser::guessValue('integer(123)')
        );
        self::assertSame('123', $value->toString());
    }

    public function test_it_should_create_float(): void
    {
        self::assertInstanceOf(
            FloatValue::class,
            $value = RecordValueGuesser::guessValue('float(12.34)')
        );
        self::assertSame('12.34', $value->toString());
    }

    public function test_it_should_create_bool(): void
    {
        self::assertInstanceOf(
            BooleanValue::class,
            $value = RecordValueGuesser::guessValue('boolean(true)')
        );
        self::assertSame('true', $value->toString());
        self::assertInstanceOf(
            BooleanValue::class,
            $value = RecordValueGuesser::guessValue('boolean(false)')
        );
        self::assertSame('false', $value->toString());
    }

    public function test_it_should_create_empty(): void
    {
        self::assertInstanceOf(
            EmptyValue::class,
            $value = RecordValueGuesser::guessValue('empty()')
        );
        self::assertInstanceOf(
            EmptyValue::class,
            $value = RecordValueGuesser::guessValue('empty()')
        );
        self::assertInstanceOf(
            EmptyValue::class,
            $value = RecordValueGuesser::guessValue('string()')
        );
    }
}
