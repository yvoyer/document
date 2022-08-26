<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Assert\Assertion;
use Star\Component\Identity\Identity;

final class DocumentId implements Identity
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

    public function entityClass(): string
    {
        return DocumentRecord::class;
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
        return self::fromString(\uniqid('record-'));
    }
}
