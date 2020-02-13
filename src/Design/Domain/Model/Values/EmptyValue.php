<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

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

    public function toReadableString(): string
    {
        return $this->toString();
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function count(): int
    {
        return 0;
    }
}
