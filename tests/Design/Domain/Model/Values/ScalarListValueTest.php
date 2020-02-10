<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use PHPUnit\Framework\TestCase;

final class ScalarListValueTest extends TestCase
{
    public function test_it_should_not_allow_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('List of scalars is empty, but "int[] | string[]" was expected.');
        ScalarListValue::fromArray([]);
    }

    public function test_it_should_not_be_empty(): void
    {
        $value = ScalarListValue::withElements(1);
        $this->assertFalse($value->isEmpty());
    }

    public function test_it_should_be_converted_to_string(): void
    {
        $this->assertSame('1', ScalarListValue::withElements(1)->toString());
        $this->assertSame('1;2;3', ScalarListValue::withElements(3)->toString());
    }

    public function test_it_should_be_converted_to_label_string(): void
    {
        $this->assertSame('list([1])', ScalarListValue::withElements(1)->getType());
        $this->assertSame('list([1,2,3])', ScalarListValue::withElements(3)->getType());
    }

    public function test_it_should_not_allow_zero_elements(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of scalar "0" is not greater than "0".');
        ScalarListValue::withElements(0);
    }
}
