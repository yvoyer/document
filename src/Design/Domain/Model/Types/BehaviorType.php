<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Assert\Assertion;
use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Behavior\DocumentBehavior;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class BehaviorType implements PropertyType
{
    /**
     * @var DocumentBehavior
     */
    private $behavior;

    public function __construct(DocumentBehavior $behavior)
    {
        $this->behavior = $behavior;
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        return $this->behavior->toWriteFormat($value);
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        return $this->behavior->toReadFormat($value);
    }

    public function doBehavior(string $property, RecordValue $value): RecordValue
    {
        return $this->behavior->onPerform($property, $value);
    }

    public function generateExceptionForNotSupportedTypeForValue(
        PropertyCode $property,
        RecordValue $value
    ): NotSupportedTypeForValue {
        return new NotSupportedTypeForValue($property, $value, $this);
    }

    public function generateExceptionForNotSupportedValue(
        PropertyCode $property,
        RecordValue $value
    ): InvalidPropertyValue {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function supportsType(RecordValue $value): bool
    {
        return $this->behavior->supportsType($value);
    }

    public function supportsValue(RecordValue $value): bool
    {
        return $this->behavior->supportsValue($value);
    }

    public function toData(): TypeData
    {
        return $this->behavior->toTypeData();
    }

    public function toHumanReadableString(): string
    {
        return $this->behavior->toHumanReadableString();
    }

    public static function fromData(array $arguments): PropertyType
    {
        Assertion::keyExists($arguments, 'behavior_class');
        $behaviorClass = $arguments['behavior_class'];
        /**
         * @var DocumentBehavior|string $behaviorClass
         */
        Assertion::subclassOf($behaviorClass, DocumentBehavior::class);

        return new static($behaviorClass::fromData($arguments));
    }
}
