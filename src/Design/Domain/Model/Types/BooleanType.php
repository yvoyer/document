<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Common\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\BooleanValue;

final class BooleanType implements PropertyType
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        return in_array($value, [1, 0, true, false, 'true', 'false', '1', '0'], true);
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
            throw new InvalidPropertyValue($propertyName, 'boolean', $rawValue);
        }

        if ($rawValue === 'false') {
            $rawValue = false;
        }

        return new BooleanValue($propertyName, (bool) $rawValue);
    }
}
