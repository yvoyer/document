<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Behavior;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;

/**
 * A Behavior gets attached to a subject. Each operations performed by the subject is broadcast to the behaviors.
 */
abstract class DocumentBehavior
{
    /**
     * @param mixed[] $arguments
     */
    final private function __construct(array $arguments)
    {
        $this->onInit($arguments);
    }

    /**
     * @param mixed[] $arguments
     */
    abstract protected function onInit(array $arguments): void;

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    abstract public function supportsType(RecordValue $value): bool;

    abstract public function supportsValue(RecordValue $value): bool;

    abstract public function toHumanReadableString(): string;

    /**
     * Execute the behavior, return the new value.
     *
     * @param string $property
     * @param RecordValue $value
     * @return RecordValue
     */
    abstract public function onPerform(string $property, RecordValue $value): RecordValue;

    abstract public function toTypeData(): TypeData;

    /**
     * @param mixed[] $arguments
     * @return static
     */
    final public static function fromData(array $arguments): DocumentBehavior
    {
        return new static($arguments);
    }
}
