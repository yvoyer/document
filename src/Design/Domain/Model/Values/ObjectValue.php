<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class ObjectValue implements RecordValue
{
    /**
     * @var object
     */
    private $object;

    /**
     * @param object $object
     */
    public function __construct($object)
    {
        Assertion::isObject($object);
        $this->object = $object;
    }

    public function toString(): string
    {
        return \get_class($this->object);
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function count(): int
    {
        return 1;
    }

    public function toTypedString(): string
    {
        return \sprintf('object(%s)', $this->toString());
    }

    public function toReadableString(): string
    {
        return $this->toString();
    }
}
