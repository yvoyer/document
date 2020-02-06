<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class ArrayTransformerTest extends TestCase
{
    public function test_it_should_pass_the_raw_value_to_all_transformers(): void
    {
        $transformer = new ArrayTransformer(
            $t1 = $this->createMock(ValueTransformer::class),
            $t2 = $this->createMock(ValueTransformer::class)
        );

        $t1->expects($this->once())
            ->method('transform')
            ->with('raw value')
            ->willReturn(StringValue::fromString('value of t1'));

        $t2->expects($this->once())
            ->method('transform')
            ->with('value of t1')
            ->willReturn(StringValue::fromString('value of t2'));

        $this->assertSame('value of t2', $transformer->transform('raw value')->toString());
    }
}
