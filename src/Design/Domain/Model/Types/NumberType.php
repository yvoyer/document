<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Values\NumberValue;

final class NumberType implements PropertyType
{
    /**
     * @param mixed $value
     * @return bool
     */
    private function isValid($value): bool
    {
        return \is_numeric($value);
    }

    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        if (! $this->isValid($rawValue)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'number', $rawValue);
        }

        if (is_int($rawValue) || (int) $rawValue == $rawValue) {
            return new NumberValue($propertyName, (int) $rawValue);
        }

        return FloatValue::fromString($propertyName, (string) $rawValue);
    }

    public function toString(): string
    {
        return 'number';
    }
}
