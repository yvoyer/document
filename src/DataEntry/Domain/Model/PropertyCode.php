<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use function uniqid;

final class PropertyCode
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function matchCode(PropertyCode $name): bool
    {
        return $name->toString() === $this->toString();
    }

    public function toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function random(): self
    {
        return new self(uniqid('property-code-'));
    }
}
