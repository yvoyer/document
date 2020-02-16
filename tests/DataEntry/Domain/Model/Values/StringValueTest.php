<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\StringValue;

final class StringValueTest extends TestCase
{
    public function test_it_should_be_created_from_string(): void
    {
        $this->assertSame('string(string)', StringValue::fromString('string')->toTypedString());
    }
}
