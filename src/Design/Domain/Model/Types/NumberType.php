<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Values\IntegerValue;

final class NumberType implements PropertyType
{
    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if (! $rawValue->isNumeric()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'number', $rawValue);
        }

        if ($rawValue->isInt()) {
            return IntegerValue::fromString($rawValue->toString());
        }

        return FloatValue::fromString($rawValue->toString());
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public static function fromData(array $arguments): PropertyType
    {
        return new self();
    }
}
