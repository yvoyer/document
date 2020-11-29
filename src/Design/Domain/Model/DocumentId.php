<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Assert\Assertion;
use Star\Component\Identity\Identity;
use function uniqid;

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

    /**
     * @param DocumentId $id
     *
     * @return bool
     */
    public function matchIdentity(DocumentId $id): bool
    {
        return $id->toString() === $this->toString();
    }

    /**
     * Returns the entity class for the identity.
     *
     * @return string
     */
    public function entityClass()
    {
        return DocumentAggregate::class;
    }

    /**
     * Returns the string value of the identity.
     *
     * @return string
     */
    public function toString()
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function random(): self
    {
        return self::fromString(uniqid('document-'));
    }
}
