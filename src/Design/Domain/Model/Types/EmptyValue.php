<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class EmptyValue implements PropertyValue
{
    /**
     * @var string
     */
    private $property;

    /**
     * @param string $property
     */
    public function __construct(string $property)
    {
        $this->property = $property;
    }

    /**
     * Return the property name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->property;
    }

    /**
     * Returns the string representation of contained value.
     *
     * @return string
     */
    public function toString(): string
    {
        return '';
    }
}
