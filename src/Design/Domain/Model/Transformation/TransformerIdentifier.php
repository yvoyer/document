<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Transformation;

final class TransformerIdentifier
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public static function random(): self
    {
        return self::fromString(\uniqid('transform-'));
    }
}
