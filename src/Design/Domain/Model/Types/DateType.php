<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class DateType implements PropertyType
{
    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($rawValue->isEmpty()) {
            return new EmptyValue();
        }

        if ($rawValue->isDate()) {
            return DateValue::fromString($rawValue->toString());
        }

        if (\strtotime($rawValue->toString()) > 0 && !$rawValue->isFloat()) {
            return StringValue::fromString($rawValue->toString());
        }

        throw InvalidPropertyValue::invalidValueForType($propertyName, $this->toString(), $rawValue);
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
        return 'date';
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
