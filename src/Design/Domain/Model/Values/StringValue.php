<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

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

    private function __construct(string $property, string $value)
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

    public static function fromString(string $property, string $value): self
    {
        return new self($property, $value);
    }
}
