<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class MaximumLengthTest extends TestCase
{
    /**
     * @param string $value
     * @dataProvider provideValidValues
     */
    public function test_it_should_allow_value(string $value): void
    {
        $constraint = MaximumLength::fromInt(3);
        $constraint->validate('name', StringValue::fromString($value), $errors = new ErrorList());
        $this->assertCount(0, $errors);
    }

    public static function provideValidValues(): array
    {
        return [
            'empty' => [''],
            'exact' => ['abc'],
            'arabic' => ['ققق'],
        ];
    }

    /**
     * @param string $value
     * @param string $message
     * @dataProvider provideInvalidValues
     */
    public function test_it_should_not_allow_invalid_value(string $value, string $message): void
    {
        $constraint = MaximumLength::fromInt(3);
        $constraint->validate('field', StringValue::fromString($value), $errors = new ErrorList());
        $this->assertCount(1, $errors);
        $this->assertSame($message, $errors->getErrorsForProperty('field', 'en')[0]);
    }

    public static function provideInvalidValues(): array
    {
        return [
            'too long' => ['abcd', 'Property "field" is too long, expected a maximum of 3 characters, "abcd" given.'],
            'arabic' => ['قققق', 'Property "field" is too long, expected a maximum of 3 characters, "قققق" given.'],
        ];
    }
}
