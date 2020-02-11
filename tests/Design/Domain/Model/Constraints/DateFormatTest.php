<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class DateFormatTest extends TestCase
{
    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new DateFormat('Y');
        $this->assertEquals($source, DateFormat::fromData($source->toData()));
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
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Date format "!"/" is not supported.');
        new DateFormat('!"/');
    }

    /**
     * @param string $format
     * @param RecordValue $value
     * @dataProvider provideInvalidValues
     */
    public function test_it_should_not_allow_value_with_invalid_date(string $format, RecordValue $value): void
    {
        $source = new DateFormat($format);
        $errors = new ErrorList();
        $source->validate('name', $value, $errors);
        $this->assertTrue($errors->hasErrors());
        $errorsArray = $errors->getErrorsForProperty('name', 'en');
        $this->assertCount(1, $errorsArray);
        $this->assertStringContainsString(
            'The date value "' . $value->toTypedString() . '" is not in format "' . $format . '", ' .
            'provided date resulted in "',
            $errorsArray[0]
        );
    }

    public static function provideInvalidValues(): array
    {
        return [
            ['Y-m-d', StringValue::fromString('2000-10-02 12:30:33')],
            ['Y-F-d', StringValue::fromString('2000-10-02')],
            ['Y-F-d', StringValue::fromString('Not valid')],
        ];
    }

    /**
     * @param $value
     * @param string $format
     * @dataProvider provideValidValue
     */
    public function test_it_should_allow_value_with_valid_date(string $value, string $format): void
    {
        $source = new DateFormat($format);
        $errors = new ErrorList();
        $source->validate('name', StringValue::fromString($value), $errors);
        $this->assertFalse($errors->hasErrors());
    }

    public static function provideValidValue(): array
    {
        return [
            ['2000-10-02', 'Y-m-d'],
            ['2000-10', 'Y-m'],
            ['2000-February-02', 'Y-F-d'],
            ['2000', 'Y'],
            ['2010-December-10', 'Y-F-d'],
        ];
    }
}
