<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

interface RecordValue extends \Countable
{
    /**
     * Returns the string representation of contained value.
     *
     * @return string
     */
    public function toString(): string;

    public function isEmpty(): bool;
}
