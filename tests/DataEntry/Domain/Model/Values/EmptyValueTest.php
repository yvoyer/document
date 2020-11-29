<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\EmptyValue;

final class EmptyValueTest extends TestCase
{
    public function test_it_should_be_created_from_mixed(): void
    {
        $this->assertSame('empty()', EmptyValue::fromString('whatever')->toTypedString());
    }
}
