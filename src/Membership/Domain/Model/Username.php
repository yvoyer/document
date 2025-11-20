<?php declare(strict_types=1);

namespace Star\Component\Document\Membership\Domain\Model;

final class Username
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    final public function toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
