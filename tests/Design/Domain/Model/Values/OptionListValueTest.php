<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use PHPUnit\Framework\TestCase;

final class OptionListValueTest extends TestCase
{
    public function test_it_should_not_allow_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('List of options is empty, but "ListOptionValue[]" was expected.');
        OptionListValue::fromArray([]);
    }

    public function test_it_should_not_be_empty(): void
    {
        $value = OptionListValue::withElements(1);
        $this->assertFalse($value->isEmpty());
    }

    public function test_it_should_be_converted_to_string(): void
    {
        $this->assertSame('1', OptionListValue::withElements(1)->toString());
        $this->assertSame('1;2;3', OptionListValue::withElements(3)->toString());
    }

    public function test_it_should_be_converted_to_labels(): void
    {
        $this->assertSame('[Label 1]', OptionListValue::withElements(1)->getType());
        $this->assertSame('[Label 1;Label 2;Label 3]', OptionListValue::withElements(3)->getType());
    }

    public function test_it_should_not_allow_zero_elements(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of options "0" is not greater than "0".');
        OptionListValue::withElements(0);
    }
}
