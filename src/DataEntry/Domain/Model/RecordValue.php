<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

interface RecordValue extends \Countable
{
    const LIST_SEPARATOR = ';';

    /**
     * Returns the string representation of contained value.
     *
     * @return string
     */
    public function toString(): string;

    public function getType(): string;

    public function isEmpty(): bool;
}
