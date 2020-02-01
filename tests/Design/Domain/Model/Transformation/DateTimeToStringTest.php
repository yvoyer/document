<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use PHPUnit\Framework\TestCase;

final class DateTimeToStringTest extends TestCase
{
    /**
     * @param string $expected
     * @param \DateTimeInterface $raw
     *
     * @dataProvider provideValidValues
     */
    public function test_it_should_convert_date_time_to_format(string $expected, $raw): void
    {
        $transformer = new DateTimeToString('Y-m-d');
        $this->assertSame($expected, $transformer->transform($raw));
    }

    public static function provideValidValues(): array
    {
        return [
            'immutable date' => ['2000-01-01', new \DateTimeImmutable('2000-01-01 12:22:33')],
            'datetime' => ['2000-01-01', new \DateTime('2000-01-01 12:22:33')],
            'now' => [\date('Y-m-d'), new \DateTime()],
        ];
    }

    public function test_it_should_throw_exception_when_raw_value_not_date_time(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Raw value "123" is not a supported type, supported types: ');
        $transformer = new DateTimeToString('Y');
        $transformer->transform(123);
    }

    public static function providerNotSupportedValues(): array
    {
        return [
            'array' => [['val']],
            'object' => [new \stdClass()],
        ];
    }

    public function test_it_should_allow_empty_string(): void
    {
        $transformer = new DateTimeToString('invalid');
        $this->assertSame('', $transformer->transform(''));
    }

    public function test_it_should_allow_null(): void
    {
        $transformer = new DateTimeToString('invalid');
        $this->assertSame('', $transformer->transform(null));
    }
}
