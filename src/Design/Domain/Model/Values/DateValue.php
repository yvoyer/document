<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class DateValue implements PropertyValue
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var \DateTimeInterface
     */
    private $value;

    /**
     * @param string $propertyName
     * @param \DateTimeInterface $value
     */
    public function __construct(string $propertyName, \DateTimeInterface $value)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
    }

    /**
     * Return the property name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->propertyName;
    }

    /**
     * Returns the string representation of contained value.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->value->format('Y-m-d');
    }
}
