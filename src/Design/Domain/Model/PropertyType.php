<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;

interface PropertyType
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool;

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     *
     * @return PropertyValue
     * @throws InvalidPropertyValue
     */
    public function createValue(string $propertyName, $rawValue): PropertyValue;

    /**
     * @return string
     */
    public function toString(): string;
}
