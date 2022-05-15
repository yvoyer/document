<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\DomainEvent\Serialization\SerializableAttribute;
use function var_dump;

final class PropertyName implements SerializableAttribute
{
    /**
     * @var string
     */
    private string $value;

    /**
     * @var string
     */
    private string $locale;

    private function __construct(string $value, string $locale)
    {
        Assertion::notBlank($value);
        $this->value = $value;
        $this->locale = $locale;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toSerializableString(): string
    {
        return $this->toString();
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function matchCode(PropertyCode $name): bool
    {
        return $name->toString() === $this->toString();
    }

    public static function fromString(string $value, string $locale): self
    {
        return new self($value, $locale);
    }

    public static function fromSerializedString(string $value): self
    {
        var_dump($value);
        return new self($value, $locale);
    }

    public static function fixture(): self
    {
        return self::fromString(\uniqid('property-'), 'en');
    }
}
