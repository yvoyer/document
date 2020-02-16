<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;

final class ArrayOfIntegerTest extends TestCase
{
    public function test_it_should_not_be_empty(): void
    {
        $value = ArrayOfInteger::withElements(1);
        $this->assertFalse($value->isEmpty());
    }

    public function test_it_should_be_converted_to_string(): void
    {
        $this->assertSame('1', ArrayOfInteger::withElements(1)->toString());
        $this->assertSame('1;2;3', ArrayOfInteger::withElements(3)->toString());
    }

    public function test_it_should_be_converted_to_label_string(): void
    {
        $this->assertSame('list(1)', ArrayOfInteger::withElements(1)->toTypedString());
        $this->assertSame('list(1;2;3)', ArrayOfInteger::withElements(3)->toTypedString());
    }

    public function test_it_should_not_allow_zero_elements(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of scalar "0" is not greater than "0".');
        ArrayOfInteger::withElements(0);
    }
}
