<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class ListValue implements PropertyValue
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var PropertyValue[]
     */
    private $values;

    /**
     * @param string $property
     * @param PropertyValue[] ...$value
     */
    public function __construct(string $property, PropertyValue ...$value)
    {
        $this->property = $property;
        $this->values = $value;
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
        return implode(
            ';',
            array_map(
                function (PropertyValue $value) {
                    return $value->toString();
                },
                $this->values
            )
        );
    }
}
