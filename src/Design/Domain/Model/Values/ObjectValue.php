<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class ObjectValue implements RecordValue
{
    /**
     * @var object
     */
    private $object;

    public function __construct(object $object)
    {
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

    public function count()
    {
        return 1;
    }

    public function getType(): string
    {
        return \sprintf('object(%s)', $this->toString());
    }
}
