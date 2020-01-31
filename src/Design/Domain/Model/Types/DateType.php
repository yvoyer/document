<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;

final class DateType implements PropertyType
{
    /**
     * @param DateTimeInterface|string $value
     * @return bool
     */
    private function isValid($value): bool
    {
        if ($value instanceof DateTimeInterface) {
            return true;
        }

        if (is_numeric($value)) {
            return false;
        }

        if (is_string($value)) {
            try {
                new DateTimeImmutable($value);
                return true;
            } catch (\Throwable $exception) {
                // invalid format
            }
        }

        return false;
    }

    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        if (! $this->isValid($rawValue)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'date', $rawValue);
        }

        if (! $rawValue instanceof DateTimeInterface) {
            $rawValue = new DateTimeImmutable($rawValue);
        }

        return new DateValue($propertyName, $rawValue);
    }

    public function toString(): string
    {
        return 'date';
    }
}
