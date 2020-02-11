<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class BeforeDateTest extends TestCase
{
    /**
     * @dataProvider provideValidValues
     *
     * @param string $value
     */
    public function test_it_should_be_valid(string $value): void
    {
        $constraint = new BeforeDate('2000-04-05 00:00:00');
        $constraint->validate(
            'name', DateValue::fromString($value), $errors = new ErrorList()
        );
        $this->assertFalse($errors->hasErrors());
        $this->assertCount(0, $errors);
    }

    public static function provideValidValues(): array
    {
        return [
            'past year' => ['1999-04-05'],
            'past month' => ['2000-03-05'],
            'past day' => ['2000-04-04'],
            'empty' => [''],
        ];
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @param string $value
     */
    public function test_it_should_be_invalid(string $value): void
    {
        $constraint = new BeforeDate('2000-05-05 02:03:04');
        $constraint->validate(
            $name = 'prop', DateValue::fromString($value), $errors = new ErrorList()
        );
        $this->assertCount(1, $errors);
        $this->assertCount(1, $errors->getErrorsForProperty($name, 'en'));
        $this->assertStringContainsString(
            'The property "prop" only accepts date before "2000-05-05"',
            $errors->getErrorsForProperty($name, 'en')[0]
        );
    }

    public static function provideInvalidValues(): array
    {
        return [
            'past hour' => ['2000-05-05 01:03:04'],
            'past minute' => ['2000-05-05 02:02:04'],
            'past second' => ['2000-05-05 02:03:03'],
            'greater day' => ['2000-05-06'],
        ];
    }

    public function test_it_should_be_build_from_constraint_data(): void
    {
        $source = new BeforeDate('2000-10-20');
        $this->assertEquals($source, BeforeDate::fromData($source->toData()));
    }
}