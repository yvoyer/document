<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class DateTypeTest extends TypeTest
{
    public function getType(): PropertyType
    {
        return new DateType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [
                true, 'The property "name" expected a "date" value, "true" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "date" value, "false" given.'
            ],
            "String value should be invalid" => [
                'invalid', 'The property "name" expected a "date" value, "invalid" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', 'The property "name" expected a "date" value, "12.34" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "date" value, "12.34" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "date" value, "34" given.'
            ],
            "Array should be invalid" => [
                [], 'The property "name" expected a "date" value, "a:0:{}" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "date" value, "stdClass" given.'
            ],
            "null should be invalid" => [
                null, 'The property "name" expected a "date" value, "NULL" given.'
            ],
        ];
    }

    public function test_it_should_set_as_text_value()
    {
        $this->assertInstanceOf(
            DateValue::class,
            $value = $this->getType()->createValue('date', '2001-01-01')
        );
        $this->assertSame('2001-01-01', $value->toString());
    }

    public function test_it_should_set_as_date_time_value()
    {
        $this->assertInstanceOf(
            DateValue::class,
            $value = $this->getType()->createValue(
                'date',
                new \DateTimeImmutable('2002-02-05 12:34:56')
            )
        );
        $this->assertSame('2002-02-05', $value->toString());
    }

    public function test_it_should_throw_exception_when_invalid_date_format()
    {
        $this->expectException(InvalidPropertyValue::class);
        $this->expectExceptionMessage('The property "name" expected a "date" value, "sadsafjksbsadjn" given.');
        $this->getType()->createValue('name', 'sadsafjksbsadjn');
    }
}
