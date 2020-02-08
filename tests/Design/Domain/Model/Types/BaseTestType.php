<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\PropertyType;

abstract class BaseTestType extends TestCase
{
    /**
     * @return PropertyType
     */
    abstract protected function getType(): PropertyType;

    /**
     * @param mixed $value
     *
     * @dataProvider provideInvalidValuesExceptions
     */
    final public function test_it_should_throw_exception_when_setting_invalid_value($value, string $message)
    {
        $this->expectException(InvalidPropertyValue::class);
        $this->expectExceptionMessage($message);
        $this->getType()->createValue('name', $value);
    }
}
