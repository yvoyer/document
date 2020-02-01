<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class StringType implements PropertyType
{
    /**
     * @param mixed $value
     * @return bool
     */
    private function isValid($value): bool
    {
        return \is_string($value);
    }

    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        if (! $this->isValid($rawValue)) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'string', $rawValue);
        }

        return StringValue::fromString($propertyName, $rawValue);
    }

    public function toString(): string
    {
        return 'string';
    }
}
