<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class NullType implements PropertyType
{
    public function validateRawValue(string $propertyName, $rawValue): ErrorList
    {
        throw new \RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
    }

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
