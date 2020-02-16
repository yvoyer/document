<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;
use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function get_class;
use function sprintf;

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
        return get_class($this->object);
    }

    public function count(): int
    {
        return 1;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function isList(): bool
    {
        return false;
    }

    public function toTypedString(): string
    {
        return sprintf('object(%s)', $this->toString());
    }

    /**
     * @param mixed[] $value
     * @return RecordValue
     */
    public static function fromArray(array $value): RecordValue
    {
        return new self((object) $value);
    }

    public static function fromString(string $value): RecordValue
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet');
    }
}
