<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Test;

use Star\Component\Document\Design\Domain\Model\DocumentOwner;

final class NullOwner implements DocumentOwner
{
    public function toString(): string
    {
        return 'null-owner';
    }

    public function toSerializableString(): string
    {
        return $this->toString();
    }
}
