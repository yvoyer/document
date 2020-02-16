<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Types;

use InvalidArgumentException;
use Star\Component\Document\Design\Domain\Model\Behavior\DocumentBehavior;
use Star\Component\Document\Design\Domain\Model\Types\BehaviorType;
use PHPUnit\Framework\TestCase;

final class BehaviorTypeTest extends TestCase
{
    public function test_throw_exception_when_missing_class(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Array does not contain an element with key "behavior_class"');
        BehaviorType::fromData([]);
    }

    public function test_throw_exception_when_class_not_valid_type(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class "invalid" was expected to be subclass of "' . DocumentBehavior::class);
        BehaviorType::fromData(['behavior_class' => 'invalid']);
    }
}
