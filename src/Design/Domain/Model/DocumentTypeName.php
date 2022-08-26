<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Translation\Domain\Model\TranslationLocale;
use Star\Component\DomainEvent\Serialization\SerializableAttribute;
use function mb_strlen;
use function serialize;
use function trim;
use function uniqid;
use function unserialize;

final class DocumentTypeName implements SerializableAttribute
{
    private string $name;
    private TranslationLocale $locale;

    public function __construct(string $name, TranslationLocale $locale)
    {
        $this->name = trim($name);
        $this->locale = $locale;
    }

    final public function isEmpty(): bool
    {
        return mb_strlen($this->name) === 0;
    }

    final public function toSerializableString(): string
    {
        return serialize(['content' => $this->name, 'locale' => $this->locale->toString()]);
    }

    final public function toString(): string
    {
        return $this->name;
    }

    final public function locale(): string
    {
        return $this->locale->toString();
    }

    public static function fromSerializedString(string $payload): DocumentTypeName
    {
        $data = unserialize($payload);

        return self::fromLocalizedString((string) $data['content'], (string) $data['locale']);
    }

    public static function fromLocalizedString(string $name, string $locale): DocumentTypeName
    {
        return new self($name, TranslationLocale::fromString($locale));
    }

    public static final function random(): self
    {
        return self::fromLocalizedString(uniqid('document-name-'), 'en');
    }
}
