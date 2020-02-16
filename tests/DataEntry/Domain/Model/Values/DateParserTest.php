<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateParser;

final class DateParserTest extends TestCase
{
    /**
     * @param string $format
     * @dataProvider provideValidFormats
     */
    public function test_it_should_not_throw_exception_when_valid_format(string $format): void
    {
        DateParser::assertValidFormat($format);
        $this->expectNotToPerformAssertions();
    }

    public static function provideValidFormats(): array
    {
        return [
            ['Y-m-d H:i:s'],
            ['y/d/m'],
            ['D-M-Y'],
            ['m-y-d'],
            ['h:i:s'],
            ['F'],
        ];
    }

    public function test_it_should_throw_exception_when_invalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Date format "invalid" is not supported.');
        DateParser::assertValidFormat('invalid');
    }
}
