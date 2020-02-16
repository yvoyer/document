<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Values\IntegerValue;

final class NumberType implements PropertyType
{
    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($rawValue->isEmpty()) {
            return new EmptyValue();
        }

        if (! $rawValue->isNumeric()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, $this->toString(), $rawValue);
        }

        if ($rawValue->isInt()) {
            return IntegerValue::fromString($rawValue->toString());
        }

        return FloatValue::fromString($rawValue->toString());
    }

    public function createDefaultValue(): RecordValue
    {
        return new EmptyValue();
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public function toString(): string
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
