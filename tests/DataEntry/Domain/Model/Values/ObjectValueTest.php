<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\DataEntry\Domain\Model\Values;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\DataEntry\Domain\Model\Values\ObjectValue;

final class ObjectValueTest extends TestCase
{
    public function test_it_should_be_created_from_array(): void
    {
        $this->assertSame('object(stdClass)', ObjectValue::fromArray(['id' => 34])->toTypedString());
    }

    public function test_it_should_be_created_with_object(): void
    {
        $this->assertSame('object(stdClass)', (new ObjectValue((object) ['id' => 34]))->toTypedString());
    }
}
