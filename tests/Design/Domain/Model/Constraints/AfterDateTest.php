<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\DataEntry\Domain\Model\Values\DateValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Constraints\AfterDate;

final class AfterDateTest extends TestCase
{
    /**
     * @dataProvider provideValidValues
     *
     * @param RecordValue $value
     */
    public function test_it_should_be_valid(RecordValue $value): void
    {
        $constraint = new AfterDate('2000-04-05 00:00:00');
        $constraint->validate('name', $value, $errors = new ErrorList());
        $this->assertCount(0, $errors);
    }

    public static function provideValidValues(): array
    {
        return [
            'future year' => [DateValue::fromString('2001-04-05')],
            'future month' => [DateValue::fromString('2000-05-05')],
            'future day' => [DateValue::fromString('2000-04-06')],
            'empty' => [new EmptyValue()],
        ];
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @param RecordValue $value
     */
    public function test_it_should_be_invalid(RecordValue $value): void
    {
        $constraint = new AfterDate('2000-05-05 02:03:04');
        $constraint->validate($name = 'name', $value, $errors = new ErrorList());
        $this->assertCount(1, $errors);
        $this->assertCount(1, $errors->getErrorsForProperty($name, 'en'));
        $this->assertStringContainsString(
            'The property "name" only accepts date after "2000-05-05"',
            $errors->getErrorsForProperty($name, 'en')[0]
        );
    }

    public static function provideInvalidValues(): array
    {
        return [
            'future hour' => [DateValue::fromString('2000-05-05 03:03:04', 'Y-m-d H:i:s')],
            'future minute' => [DateValue::fromString('2000-05-05 02:04:04', 'Y-m-d H:i:s')],
            'future second' => [DateValue::fromString('2000-05-05 02:03:05', 'Y-m-d H:i:s')],
            'past day' => [DateValue::fromString('2000-05-04')],
        ];
    }

    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new AfterDate('2000-10-20');
        $this->assertEquals($source, AfterDate::fromData($source->toData()));
    }
}
