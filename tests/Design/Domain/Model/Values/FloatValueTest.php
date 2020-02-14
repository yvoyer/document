<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use PHPUnit\Framework\TestCase;

final class FloatValueTest extends TestCase
{
    public function test_it_be_created_from_float(): void
    {
        $this->assertSame('123.456', FloatValue::fromFloat(123.456)->toString());
        $this->assertSame('0.456', FloatValue::fromFloat(.456)->toString());
        $this->assertSame('123', FloatValue::fromFloat(123)->toString());
    }

    public function test_it_be_created_from_int(): void
    {
        $this->assertSame('1234.56', FloatValue::fromInt(123456, 2)->toString());
    }

    public function test_it_be_created_from_string(): void
    {
        $this->assertSame('123456.98', FloatValue::fromString('123456.98')->toString());
        $this->assertSame('123456', FloatValue::fromString('123456')->toString());
        $this->assertSame('0.123456', FloatValue::fromString('0.123456')->toString());
    }
}
