<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Values\NumberValue;

final class NumberType implements PropertyType
{
    public function validateRawValue(string $propertyName, $rawValue): ErrorList
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     * @return RecordValue
     */
    public function createValue(string $propertyName, $rawValue): RecordValue
    {
        if (! \is_numeric($rawValue)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'number', $rawValue);
        }

        if (\is_int($rawValue) || (int) $rawValue == $rawValue) {
            return NumberValue::fromInt((int) $rawValue);
        }

        return FloatValue::fromString((string) $rawValue);
    }

    public function toString(): string
    {
        return 'number';
    }
}
