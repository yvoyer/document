<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Assert\Assertion;
use Star\Component\DomainEvent\Serialization\SerializableAttribute;
use Star\Component\Identity\Identity;
use function uniqid;

final class DocumentTypeId implements Identity, SerializableAttribute
{
    private string $value;

    private function __construct(string $value)
    {
        Assertion::notBlank($value);
        $this->value = $value;
    }

    /**
     * @param DocumentTypeId $id
     *
     * @return bool
     */
    public function matchIdentity(DocumentTypeId $id): bool
    {
        return $id->toString() === $this->toString();
    }

    public function entityClass(): string
    {
        return DocumentTypeAggregate::class;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toSerializableString(): string
    {
        return $this->toString();
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function random(): self
    {
        return self::fromString(uniqid('document-type_'));
    }
}
