<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

use PHPUnit\Framework\TestCase;

final class StringToDateTimeTest extends TestCase
{
    public function test_it_should_add_value_transformer_to_property()
    {
        $transformer = new StringToDateTime();
        $value = $transformer->transform('2000-10-01');
        $this->assertSame('2000-10-01', $value->toString());
    }

    public function test_it_should_allow_empty_value(): void
    {
        $transformer = new StringToDateTime();
        $this->assertSame('', $transformer->transform('')->toString());
    }

    public function test_it_should_allow_null_value(): void
    {
        $transformer = new StringToDateTime();
        $this->assertSame('', $transformer->transform(null)->toString());
    }
}
