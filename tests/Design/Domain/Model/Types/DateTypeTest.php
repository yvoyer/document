<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyType;

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
                [], 'The property "name" expected a "date" value, "[]" given.'
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
        $this->assertSame(
            '2001-01-01',
            $this->getType()->createValue('date', '2001-01-01')->toString()
        );
    }

    public function test_it_should_set_as_date_time_value()
    {
        $this->assertSame(
            '2002-02-05',
            $this->getType()
                ->createValue('date', new \DateTimeImmutable('2002-02-05 12:34:56'))
                ->toString()
        );
    }

    public function test_it_should_throw_exception_when_invalid_date_format()
    {
        $this->expectException(InvalidPropertyValue::class);
        $this->expectExceptionMessage('The property "name" expected a "date" value, "sadsafjksbsadjn" given.');
        $this->getType()->createValue('name', 'sadsafjksbsadjn');
    }

    public function test_it_should_create_empty_value_when_null(): void
    {
        $this->assertSame('', $this->getType()->createValue('date', '')->toString());
    }

    public function test_it_should_create_string_value_when_string(): void
    {
        $this->assertSame(
            'Feb',
            $this->getType()->createValue('date', 'Feb')->toString()
        );
    }
}
