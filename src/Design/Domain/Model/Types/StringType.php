<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Common\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class StringType implements PropertyType
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        return is_string($value);
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
            throw new InvalidPropertyValue($propertyName, 'string', $rawValue);
        }

        return new StringValue($propertyName, $rawValue);
    }
}
