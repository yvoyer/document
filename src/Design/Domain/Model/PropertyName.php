<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Assert\Assertion;

final class PropertyName
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        Assertion::notBlank($value);
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function matchName(PropertyName $name): bool
    {
        return $name->toString() === $this->toString();
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function fixture(): self
    {
        return self::fromString(\uniqid('property-'));
    }
}
