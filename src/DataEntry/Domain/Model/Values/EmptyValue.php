<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class EmptyValue implements RecordValue
{
    public function toString(): string
    {
        return '';
    }

    public function toTypedString(): string
    {
        return 'empty()';
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function isList(): bool
    {
        return false;
    }

    public function count(): int
    {
        return 0;
    }

    public static function fromString(string $value): RecordValue
    {
        return new self();
    }
}
