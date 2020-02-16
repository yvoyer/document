<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Countable;

interface RecordValue extends Countable
{
    const LIST_SEPARATOR = ';';

    /**
     * Returns the string representation of contained value.
     *
     * @return string
     */
    public function toString(): string;

    /**
     * @return string The type of the value with readable format. ie "int(34)"
     */
    public function toTypedString(): string;

    public function isEmpty(): bool;

    public function isList(): bool;

    public static function fromString(string $value): RecordValue;
}
