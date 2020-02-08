<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class ArrayTransformerTest extends TestCase
{
    public function test_it_should_pass_the_raw_value_to_all_transformers(): void
    {
        $transformer = new ArrayTransformer(
            new class implements ValueTransformer {
                public function transform($rawValue): RecordValue
                {
                    return StringValue::fromString('value 1');
                }
            },
            new class implements ValueTransformer {
                public function transform($rawValue): RecordValue
                {
                    return StringValue::fromString('value 2');
                }
            }
        );

        $this->assertSame('value 2', $transformer->transform('raw value')->toString());
    }
}
