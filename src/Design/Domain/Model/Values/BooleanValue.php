<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class BooleanValue implements PropertyValue
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var bool
     */
    private $value;

    /**
     * @param string $property
     * @param bool $value
     */
    public function __construct(string $property, bool $value)
    {
        $this->property = $property;
        $this->value = $value;
    }

    /**
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
        return ($this->value) ? 'true' : 'false';
    }
}
