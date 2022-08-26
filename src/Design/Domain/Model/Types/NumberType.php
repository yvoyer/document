<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\FloatValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\IntegerValue;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class NumberType implements PropertyType
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
        return $value instanceof IntegerValue
            || $value instanceof FloatValue
            || $value->isEmpty();
    }

    public function supportsValue(RecordValue $value): bool
    {
        return $value->isEmpty() || $value instanceof IntegerValue || $value instanceof FloatValue;
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
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function doBehavior(string $property, RecordValue $value): RecordValue
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public function toHumanReadableString(): string
    {
        return 'number';
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
