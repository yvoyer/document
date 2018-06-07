<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

interface RecordValue
{
    /**
     * Returns the string representation of contained value.
     *
     * @return string
     */
    public function toString(): string;
}
