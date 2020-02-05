<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class AfterDateTest extends TestCase
{
    /**
     * @dataProvider provideValidValues
     *
     * @param string $value
     */
    public function test_it_should_be_valid(string $value): void
    {
        $constraint = new AfterDate('2000-04-05 00:00:00');
        $constraint->validate(
            PropertyName::fixture(), new \DateTimeImmutable($value), $errors = new ErrorList()
        );
        $this->assertCount(0, $errors);
    }

    public static function provideValidValues(): array
    {
        return [
            'future year' => ['2001-04-05'],
            'future month' => ['2000-05-05'],
            'future day' => ['2000-04-06'],
        ];
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @param string $value
     */
    public function test_it_should_be_invalid(string $value): void
    {
        $constraint = new AfterDate('2000-05-05 02:03:04');
        $constraint->validate(
            $name = PropertyName::fromString('name'), new \DateTimeImmutable($value), $errors = new ErrorList()
        );
        $this->assertCount(1, $errors);
        $this->assertCount(1, $errors->getErrorsForProperty($name->toString(), 'en'));
        $this->assertStringContainsString(
            'The property "name" only accepts date after "2000-05-05"',
            $errors->getErrorsForProperty($name->toString(), 'en')[0]
        );
    }

    public static function provideInvalidValues(): array
    {
        return [
            'future hour' => ['2000-05-05 03:03:04'],
            'future minute' => ['2000-05-05 02:04:04'],
            'future second' => ['2000-05-05 02:03:05'],
            'past day' => ['2000-05-04'],
        ];
    }
}
