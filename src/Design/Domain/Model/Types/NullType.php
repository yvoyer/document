<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class NullType implements PropertyType
{
    public function createValue(string $propertyName, $rawValue): RecordValue
    {
        if (! \is_null($rawValue)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'null', $rawValue);
        }

        return new EmptyValue();
    }

    public function toString(): string
    {
        return 'null';
    }
}
