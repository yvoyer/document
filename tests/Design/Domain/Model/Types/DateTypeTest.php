<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class DateTypeTest extends BaseTestType
{
    public function getType(): PropertyType
    {
        return new DateType();
    }

    public static function provideInvalidValuesExceptions(): array
    {
        return [
            "Boolean true should be invalid" => [
                true, 'The property "name" expected a "date" value, "boolean(true)" given.'
            ],
            "Boolean false should be invalid" => [
                false, 'The property "name" expected a "date" value, "boolean(false)" given.'
            ],
            "String format is not valid" => [
                'invalid', 'The property "name" expected a "date" value, "string(invalid)" given.'
            ],
            "String numeric should be invalid" => [
                '12.34', 'The property "name" expected a "date" value, "float(12.34)" given.'
            ],
            "Float should be invalid" => [
                12.34, 'The property "name" expected a "date" value, "float(12.34)" given.'
            ],
            "Integer should be invalid" => [
                34, 'The property "name" expected a "date" value, "int(34)" given.'
            ],
            "Array should be invalid" => [
                [1], 'The property "name" expected a "date" value, "list([1])" given.'
            ],
            "Object should be invalid" => [
                (object) [], 'The property "name" expected a "date" value, "object(stdClass)" given.'
            ],
        ];
    }

    public function test_it_should_set_as_text_value()
    {
        $this->assertSame(
            '2001-01-01',
            $this->getType()->createValue('date', RawValue::fromMixed('2001-01-01'))->toString()
        );
    }

    public function test_it_should_set_as_date_time_value()
    {
        $this->assertSame(
            '2002-02-05',
            $this->getType()
                ->createValue(
                    'date',
                    RawValue::fromMixed(new \DateTimeImmutable('2002-02-05 12:34:56'))
                )
                ->toString()
        );
    }

    public function test_it_should_allow_empty_string(): void
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('date', RawValue::fromMixed(''))->toString()
        );
    }

    public function test_it_should_allow_empty_array(): void
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('date', RawValue::fromMixed([]))->toString()
        );
    }

    public function test_it_should_allow_null(): void
    {
        $this->assertSame(
            '',
            $this->getType()->createValue('date', RawValue::fromMixed(null))->toString()
        );
    }

    public function test_it_should_allow_partial_string_date(): void
    {
        $this->assertSame(
            'Feb',
            $this->getType()->createValue('date', RawValue::fromMixed('Feb'))->toString()
        );
    }
}
