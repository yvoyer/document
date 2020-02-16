<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;

final class DateValueTest extends TestCase
{
    public function test_it_should_be_created_from_string(): void
    {
        $this->assertSame(
            'date(2000-01-01)',
            DateValue::fromString('2000-01-01')->toTypedString()
        );
    }

    public function test_it_should_be_created_from_date_time(): void
    {
        $this->assertSame(
            'date(2000-01-01)',
            DateValue::fromDateTime(new DateTimeImmutable('2000-01-01'))->toTypedString()
        );
    }

    public function test_it_should_create_empty(): void
    {
        $this->assertSame('empty()', DateValue::fromString('')->toTypedString());
    }

    public function test_it_should_throw_exception_when_invalid_php_format_given(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The date value format must be "Y-m-d", "invalid" given.');
        DateValue::fromString('invalid');
    }
}
