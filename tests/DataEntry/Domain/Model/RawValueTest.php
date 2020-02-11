<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use PHPUnit\Framework\TestCase;

final class RawValueTest extends TestCase
{
    public function test_it_should_create_boolean_value(): void
    {
        $this->assertBooleanValue('true', true);
        $this->assertBooleanValue('false', false);
        $this->assertBooleanValue('true', 'true');
        $this->assertBooleanValue('false', 'false');
    }

    public function test_it_should_create_string_value(): void
    {
        $this->assertStringValue('something', 'something');
    }

    public function test_it_should_create_date_value(): void
    {
        $this->assertDateValue('2001-01-01', new \DateTime('2001-01-01'));
        $this->assertDateValue('2001-01-01', new \DateTimeImmutable('2001-01-01'));
    }

    public function test_it_should_create_empty_value(): void
    {
        $this->assertEmptyValue('', []);
        $this->assertEmptyValue('', '');
    }

    public function test_it_should_create_int_value(): void
    {
        $this->assertIntegerValue('1', 1);
        $this->assertIntegerValue('0', 0);
        $this->assertIntegerValue('1234', 1234);
        $this->assertIntegerValue('123', '123');
        $this->assertIntegerValue('9223372036854775807', PHP_INT_MAX);
        $this->assertIntegerValue('-9223372036854775808', PHP_INT_MIN);
        $this->assertIntegerValue('12', '12.0');
    }

    public function test_it_should_create_float_value(): void
    {
        $this->assertFloatValue('12.34', '12.34');
        $this->assertFloatValue('12.5', 12.5);
    }

    public function test_it_should_create_list_value(): void
    {
        $this->assertArrayValue('1', [1]);
        $this->assertArrayValue('1;2;3', [1, 2, 3]);
        $this->assertArrayValue('1;2;3', ['1', '2', '3']);
    }

    private function assertBooleanValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertFalse(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isArray());
        $this->assertFalse(RawValue::fromMixed($value)->isString());
        $this->assertFalse(RawValue::fromMixed($value)->isNumeric());
        $this->assertFalse(RawValue::fromMixed($value)->isFloat());
        $this->assertFalse(RawValue::fromMixed($value)->isInt());
        $this->assertFalse(RawValue::fromMixed($value)->isObject());
        $this->assertFalse(RawValue::fromMixed($value)->isDate());
        $this->assertTrue(RawValue::fromMixed($value)->isBool());
    }

    private function assertStringValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertFalse(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isArray());
        $this->assertFalse(RawValue::fromMixed($value)->isNumeric());
        $this->assertFalse(RawValue::fromMixed($value)->isFloat());
        $this->assertFalse(RawValue::fromMixed($value)->isInt());
        $this->assertFalse(RawValue::fromMixed($value)->isObject());
        $this->assertFalse(RawValue::fromMixed($value)->isDate());
        $this->assertFalse(RawValue::fromMixed($value)->isBool());
        $this->assertTrue(RawValue::fromMixed($value)->isString());
    }

    private function assertIntegerValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertFalse(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isArray());
        $this->assertTrue(RawValue::fromMixed($value)->isString());
        $this->assertFalse(RawValue::fromMixed($value)->isFloat());
        $this->assertFalse(RawValue::fromMixed($value)->isObject());
        $this->assertFalse(RawValue::fromMixed($value)->isDate());
        $this->assertFalse(RawValue::fromMixed($value)->isBool());
        $this->assertTrue(RawValue::fromMixed($value)->isNumeric());
        $this->assertTrue(RawValue::fromMixed($value)->isInt());
    }

    private function assertFloatValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertFalse(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isArray());
        $this->assertTrue(RawValue::fromMixed($value)->isString());
        $this->assertFalse(RawValue::fromMixed($value)->isInt());
        $this->assertFalse(RawValue::fromMixed($value)->isObject());
        $this->assertFalse(RawValue::fromMixed($value)->isDate());
        $this->assertFalse(RawValue::fromMixed($value)->isBool());
        $this->assertTrue(RawValue::fromMixed($value)->isNumeric());
        $this->assertTrue(RawValue::fromMixed($value)->isFloat());
    }

    private function assertDateValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertFalse(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isArray());
        $this->assertFalse(RawValue::fromMixed($value)->isString());
        $this->assertFalse(RawValue::fromMixed($value)->isNumeric());
        $this->assertFalse(RawValue::fromMixed($value)->isFloat());
        $this->assertFalse(RawValue::fromMixed($value)->isInt());
        $this->assertFalse(RawValue::fromMixed($value)->isObject());
        $this->assertFalse(RawValue::fromMixed($value)->isBool());
        $this->assertTrue(RawValue::fromMixed($value)->isDate());
    }

    private function assertEmptyValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertTrue(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isString());
        $this->assertFalse(RawValue::fromMixed($value)->isNumeric());
        $this->assertFalse(RawValue::fromMixed($value)->isFloat());
        $this->assertFalse(RawValue::fromMixed($value)->isInt());
        $this->assertFalse(RawValue::fromMixed($value)->isDate());
        $this->assertFalse(RawValue::fromMixed($value)->isBool());
        $this->assertFalse(RawValue::fromMixed($value)->isObject());
        $this->assertFalse(RawValue::fromMixed($value)->isArray());
    }

    private function assertArrayValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertFalse(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isString());
        $this->assertFalse(RawValue::fromMixed($value)->isNumeric());
        $this->assertFalse(RawValue::fromMixed($value)->isFloat());
        $this->assertFalse(RawValue::fromMixed($value)->isInt());
        $this->assertFalse(RawValue::fromMixed($value)->isDate());
        $this->assertFalse(RawValue::fromMixed($value)->isBool());
        $this->assertFalse(RawValue::fromMixed($value)->isObject());
        $this->assertTrue(RawValue::fromMixed($value)->isArray());
    }

    private function assertObjectValue(string $stringValue, $value): void
    {
        $this->assertSame($stringValue, RawValue::fromMixed($value)->toString());
        $this->assertFalse(RawValue::fromMixed($value)->isEmpty());
        $this->assertFalse(RawValue::fromMixed($value)->isArray());
        $this->assertFalse(RawValue::fromMixed($value)->isString());
        $this->assertFalse(RawValue::fromMixed($value)->isNumeric());
        $this->assertFalse(RawValue::fromMixed($value)->isFloat());
        $this->assertFalse(RawValue::fromMixed($value)->isInt());
        $this->assertFalse(RawValue::fromMixed($value)->isDate());
        $this->assertFalse(RawValue::fromMixed($value)->isBool());
        $this->assertTrue(RawValue::fromMixed($value)->isObject());
    }
}
