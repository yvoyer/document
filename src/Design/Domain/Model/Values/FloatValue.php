<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\Design\Domain\Model\PropertyValue;

final class FloatValue implements PropertyValue
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var int The complete int value of the float ie. "12.34" => 1234
     */
    private $value;

    /**
     * @var int The number of decimal after the dot ie. "12.34" => 2 (which is 34)
     */
    private $decimal;

    public function __construct(string $property, int $value, int $decimal)
    {
        $this->property = $property;
        $this->value = $value;
        $this->decimal = $decimal;
    }

    public function getName(): string
    {
        return $this->property;
    }

    public function toString(): string
    {
        return (string) \substr_replace((string) $this->value, '.', - $this->decimal, 0);
    }

    /**
     * @param string $property
     * @param string $value A float value ie. "12.34"
     *
     * @return FloatValue
     */
    public static function fromString(string $property, string $value): self
    {
        $parts = \explode('.', $value);

        return new self($property, (int) \str_replace('.', '', $value), \mb_strlen($parts[1]));
    }
}
