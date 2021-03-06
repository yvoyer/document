<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\BooleanValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class BooleanType implements PropertyType
{
    public function toWriteFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function supportsType(RecordValue $value): bool
    {
        return $value instanceof BooleanValue || $value->isEmpty();
    }

    public function supportsValue(RecordValue $value): bool
    {
        return $value->isEmpty() || $value instanceof BooleanValue;
    }

    public function generateExceptionForNotSupportedTypeForValue(
        string $property,
        RecordValue $value
    ): NotSupportedTypeForValue {
        return new NotSupportedTypeForValue($property, $value, $this);
    }

    public function generateExceptionForNotSupportedValue(string $property, RecordValue $value): InvalidPropertyValue
    {
        return InvalidPropertyValue::invalidValueForType(
            $property,
            $value,
            $this
        );
    }

    public function doBehavior(string $property, RecordValue $value): RecordValue
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public function toHumanReadableString(): string
    {
        return 'boolean';
    }

    /**
     * @param mixed[] $arguments
     * @return PropertyType
     */
    public static function fromData(array $arguments): PropertyType
    {
        return new self();
    }
}
