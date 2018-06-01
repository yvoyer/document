<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class NumberValue implements PropertyValue
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var int
     */
    private $value;

    /**
     * @param string $property
     * @param int $value
     */
    public function __construct(string $property, int $value)
    {
        $this->property = $property;
        $this->value = $value;
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
        return strval($this->value);
    }
}
