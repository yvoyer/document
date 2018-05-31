<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Common\Domain\Model\PropertyValue;

final class StringValue implements PropertyValue
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $property
     * @param string $value
     */
    public function __construct(string $property, string $value)
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
        return $this->value;
    }
}
