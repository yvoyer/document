<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class ListOptionValue implements PropertyValue
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string|int
     */
    private $value;

    /**
     * @var string
     */
    private $label;

    /**
     * @param string $property
     * @param string|int $value
     * @param string $label
     */
    public function __construct(string $property, $value, string $label)
    {
        $this->property = $property;
        $this->value = $value;
        $this->label = $label;
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
        return $this->label;
    }
}
