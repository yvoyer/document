<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\NullValue;

final class NullType implements PropertyType
{
    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        if (! \is_null($rawValue)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'null', $rawValue);
        }

        return new NullValue($propertyName);
    }

    public function toString(): string
    {
        return 'null';
    }
}
