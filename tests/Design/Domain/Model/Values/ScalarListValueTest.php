<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use PHPUnit\Framework\TestCase;

final class ScalarListValueTest extends TestCase
{
    public function test_it_should_be_empty(): void
    {
        $value = ScalarListValue::fromArray([]);
        $this->assertTrue($value->isEmpty());
    }

    public function test_it_should_be_not_be_empty(): void
    {
        $value = ScalarListValue::withElements(1);
        $this->assertFalse($value->isEmpty());
    }

    public function test_it_should_be_converted_to_string(): void
    {
        $this->assertSame('', ScalarListValue::withElements(0)->toString());
        $this->assertSame('Label 1', ScalarListValue::withElements(1)->toString());
        $this->assertSame('Label 1;Label 2;Label 3', ScalarListValue::withElements(3)->toString());
    }
}
