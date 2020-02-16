<?php declare(strict_types=1);

namespace Star\Component\Document\Tests\Design\Domain\Model\Behavior\State;

use PHPUnit\Framework\TestCase;
use Star\Component\Document\Design\Domain\Model\Behavior\State\StateValue;

final class StateValueTest extends TestCase
{
    public function test_it_should_be_built_from_string(): void
    {
        $state = StateValue::fromString('state');
        self::assertSame('state', $state->toString());
        self::assertSame('state(state)', $state->toTypedString());
    }

    public function test_it_should_be_built_from_mixed(): void
    {
        $state = StateValue::fromString('state');
        self::assertSame('state', $state->toString());
        self::assertSame('state(state)', $state->toTypedString());
    }

    public function test_it_should_never_be_empty(): void
    {
        self::assertFalse(StateValue::fromString('state')->isEmpty());
    }

    public function test_it_should_never_be_a_list(): void
    {
        self::assertFalse(StateValue::fromString('state')->isList());
    }
}
