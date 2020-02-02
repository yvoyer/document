<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\BooleanValue;

final class BooleanType implements PropertyType
{
    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        if (! in_array($rawValue, [1, 0, true, false, 'true', 'false', '1', '0'], true)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'boolean', $rawValue);
        }

        if ($rawValue === 'false') {
            $rawValue = false;
        }

        return new BooleanValue($propertyName, (bool) $rawValue);
    }

    public function toString(): string
    {
        return 'boolean';
    }
}
