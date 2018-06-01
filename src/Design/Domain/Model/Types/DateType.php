<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class DateType implements PropertyType
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        if ($value instanceof \DateTimeInterface) {
            return true;
        }

        if (is_numeric($value)) {
            return false;
        }

        if (is_string($value)) {
            try {
                new \DateTimeImmutable($value);
                return true;
            } catch (\Throwable $exception) {
                // invalid format
            }
        }

        return false;
    }

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     *
     * @return PropertyValue
     * @throws InvalidPropertyValue
     */
    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        if (! $this->isValid($rawValue)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'date', $rawValue);
        }

        if (! $rawValue instanceof \DateTimeInterface) {
            $rawValue = new \DateTimeImmutable($rawValue);
        }

        return new DateValue($propertyName, $rawValue);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'date';
    }
}
