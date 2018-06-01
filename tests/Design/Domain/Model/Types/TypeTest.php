<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

abstract class TypeTest extends TestCase
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
        $this->assertFalse($this->getType()->isValid($value));

        $this->expectException(InvalidPropertyValue::class);
        $this->expectExceptionMessage($message);
        $this->getType()->createValue('name', $value);
    }

//    public static function provideInvalidValuesExceptions(): array
//    {
//        return [
//            "Boolean true should be invalid" => [
//                true, 'The property "name" expected a "string" value, "true" given.'
//            ],
//            "Boolean false should be invalid" => [
//                false, 'The property "name" expected a "string" value, "false" given.'
//            ],
//            "String value should be invalid" => [
//                'invalid', 'The property "name" expected a "string" value, "invalid" given.'
//            ],
//            "String numeric should be invalid" => [
//                '12.34', 'The property "name" expected a "string" value, "12.34" given.'
//            ],
//            "Float should be invalid" => [
//                12.34, 'The property "name" expected a "string" value, "12.34" given.'
//            ],
//            "Integer should be invalid" => [
//                34, 'The property "name" expected a "string" value, "34" given.'
//            ],
//            "Array should be invalid" => [
//                [], 'The property "name" expected a "string" value, "a:0:{}" given.'
//            ],
//            "Object should be invalid" => [
//                (object) [], 'The property "name" expected a "string" value, "stdClass" given.'
//            ],
//            "null should be invalid" => [
//                null, 'The property "name" expected a "string" value, "NULL" given.'
//            ],
//        ];
//    }
}
