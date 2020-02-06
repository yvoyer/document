<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use DateTimeInterface;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class DateType implements PropertyType
{
    /**
     * @param string $propertyName
     * @param mixed $rawValue
     * @return RecordValue
     */
    public function createValue(string $propertyName, $rawValue): RecordValue
    {
        if ($rawValue instanceof DateTimeInterface) {
            return DateValue::fromDateTime($rawValue);
        }

        if (\is_string($rawValue)) {
            if (empty($rawValue)) {
                return new EmptyValue();
            }

            return DateValue::fromString($rawValue);
        }

        throw InvalidPropertyValue::invalidValueForType($propertyName, 'date', $rawValue);
    }

    public function toString(): string
    {
        return 'date';
    }
}
