<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;

final class IntegerValueTest extends TestCase
{
    public function test_it_should_be_created_from_string(): void
    {
        $this->assertSame('int(23)', IntegerValue::fromString('23')->toTypedString());
    }

    public function test_it_should_be_created_from_int(): void
    {
        $this->assertSame('int(23)', IntegerValue::fromString('23')->toTypedString());
    }

    public function test_it_should_be_created_with_zero(): void
    {
        $this->assertSame('int(0)', IntegerValue::fromInt(0)->toTypedString());
    }
}
