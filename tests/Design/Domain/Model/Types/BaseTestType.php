<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Types;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

abstract class BaseTestType extends TestCase
{
    /**
     * @return PropertyType
     */
    abstract protected function getType(): PropertyType;

    abstract public static function provideInvalidValuesExceptions(): array;

    /**
     * @param RecordValue $value
     *
     * @dataProvider provideInvalidValuesExceptions
     */
    final public function test_it_should_throw_exception_when_setting_invalid_value(RecordValue $value): void
    {
        $this->assertFalse($this->getType()->supportsValue($value));
    }

    abstract public static function provideInvalidTypesOfValueExceptions(): array;

    /**
     * @param RecordValue $value
     *
     * @dataProvider provideInvalidTypesOfValueExceptions
     */
    final public function test_it_should_throw_exception_when_setting_invalid_type_of_value(RecordValue $value): void
    {
        $this->assertFalse($this->getType()->supportsType($value));
    }
}
