<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class EmptyValue implements RecordValue
{
    public function toString(): string
    {
        return '';
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
