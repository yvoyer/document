<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class MinimumLengthTest extends TestCase
{
    /**
     * @param string $value
     * @dataProvider provideValidValues
     */
    public function test_it_should_allow_value(string $value): void
    {
        $constraint = MinimumLength::fromInt(3);
        $constraint->validate('name', StringValue::fromString($value), $errors = new ErrorList());
        $this->assertCount(0, $errors);
    }

    /**
     * @return string[][]
     */
    public static function provideValidValues(): array
    {
        return [
            'exact length' => ['abc'],
            'longer' => ['abcd'],
            'arabic' => ['ققق'],
        ];
    }

    /**
     * @param mixed $value
     * @param string $message
     * @dataProvider provideInvalidValues
     */
    public function test_it_should_not_allow_invalid_value(string $value, string $message): void
    {
        $constraint = MinimumLength::fromInt(3);
        $constraint->validate($name = 'prop', StringValue::fromString($value), $errors = new ErrorList());
        $this->assertTrue($errors->hasErrors());
        $this->assertCount(1, $errors->getErrorsForProperty('prop', 'en'));
        $this->assertSame($message, $errors->getErrorsForProperty('prop', 'en')[0]);
    }

    /**
     * @return string[][]
     */
    public static function provideInvalidValues(): array
    {
        return [
            'lower' => ['ab', 'Property "prop" is too short, expected a minimum of 3 characters, "ab" given.'],
            'empty' => ['', 'Property "prop" is too short, expected a minimum of 3 characters, "" given.'],
            'arabic' => ['قق', 'Property "prop" is too short, expected a minimum of 3 characters, "قق" given.'],
        ];
    }
}
