<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Parameters;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\Parameters\DateFormat;
use function sprintf;

final class DateFormatTest extends TestCase
{
    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new DateFormat('Y');
        $this->assertEquals($source, DateFormat::fromParameterData($source->toParameterData()));
    }

    public function test_it_should_allow_empty_value(): void
    {
        $source = new DateFormat('Y-m-d');
        $errors = new ErrorList();
        $source->validate('name', new EmptyValue(), $errors);
        $this->assertFalse($errors->hasErrors());
    }

    public function test_it_should_not_allow_invalid_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Date format "!"/" is not supported.');
        new DateFormat('!"/');
    }

    /**
     * @param RecordValue $value
     * @dataProvider provideInvalidValues
     */
    public function test_it_should_not_allow_value_with_invalid_date(RecordValue $value): void
    {
        $source = new DateFormat($expectedFormat = 'Y-m-d');
        $errors = new ErrorList();
        $source->validate('name', $value, $errors);
        $this->assertTrue($errors->hasErrors());
        $errorsArray = $errors->getErrorsForProperty('name', 'en');
        $this->assertCount(1, $errorsArray);
        $this->assertStringContainsString(
            sprintf(
                'The date format expected a record value of type "date", "%s" given.',
                $value->toTypedString()
            ),
            $errorsArray[0]
        );
    }

    public static function provideInvalidValues(): array
    {
        return [
            [IntegerValue::fromInt(2000)],
        ];
    }

    /**
     * @param string $value
     * @param string $expected
     * @param string $format
     * @dataProvider provideValidValue
     */
    public function test_it_should_allow_value_with_valid_date(string $value, string $expected, string $format): void
    {
        $source = new DateFormat($format);
        self::assertSame($expected, $source->toReadFormat(DateValue::fromString($value))->toString());
    }

    public static function provideValidValue(): array
    {
        return [
            ['2000-10-02', '2000-10-02', 'Y-m-d'],
            ['2000-10-02', '2000-10', 'Y-m'],
            ['2000-10-02', '2000', 'Y'],
            'ignore empty value' => ['', '', 'Y'],
        ];
    }

    public function test_it_should_throw_exception_when_value_is_not_date(): void
    {
        $format = new DateFormat('Y');
        $format->validate('name', StringValue::fromString('invalid'), $errors = new ErrorList());
        self::assertSame(
            [
                'The date format expected a record value of type "date", "string(invalid)" given.',
            ],
            $errors->getErrorsForProperty('name', 'en')
        );
    }
}
