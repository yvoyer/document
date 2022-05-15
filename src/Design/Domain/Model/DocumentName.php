<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Assert\Assertion;
use Star\Component\DomainEvent\Serialization\SerializableAttribute;
use function serialize;
use function unserialize;

final class DocumentName implements SerializableAttribute
{
    private string $name;
    private string $locale;

    public function __construct(string $name, string $locale)
    {
        Assertion::notEmpty($name, 'Document name "%s" is empty, but non empty value was expected.');
        $this->name = $name;
        $this->locale = $locale;
    }

    final public function toSerializableString(): string
    {
        return serialize(['content' => $this->name, 'locale' => $this->locale]);
    }

    final public function locale(): string
    {
        return $this->locale;
    }

    public static function defaultName(): self
    {
        return new self('default-name', 'en');
    }

    public static function fromSerializedString(string $payload): DocumentName
    {
        $data = unserialize($payload);
        return new self($data['content'], $data['locale']);
    }
}
