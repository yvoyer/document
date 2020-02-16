<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;

final class BooleanValueTest extends TestCase
{
    public function test_it_should_create_from_string(): void
    {
        $this->assertSame('boolean(false)', BooleanValue::fromString('false')->toTypedString());
        $this->assertSame('boolean(true)', BooleanValue::fromString('true')->toTypedString());
    }

    public function test_it_should_create_empty_value(): void
    {
        $this->assertSame('empty()', BooleanValue::fromString('')->toTypedString());
    }
}
