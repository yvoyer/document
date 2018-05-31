<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Star\Component\Document\Common\Domain\Model\PropertyValue;

interface DocumentRecord extends ReadOnlyRecord
{
    /**
     * @param string $propertyName
     * @param mixed $value
     */
    public function setValue(string $propertyName, $value);

    /**
     * @param string $propertyName
     *
     * @return PropertyValue
     */
    public function getValue(string $propertyName): PropertyValue;
}
