<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Translation\Domain\Model\TranslationLocale;
use Star\Component\DomainEvent\Serialization\SerializableAttribute;
use function mb_strlen;
use function serialize;
use function sprintf;
use function unserialize;

final class PropertyName implements SerializableAttribute
{
    private string $value;
    private TranslationLocale $locale;

    private function __construct(string $value, TranslationLocale $locale)
    {
        if (mb_strlen($value) === 0) {
            throw new InvalidPropertyName(
                sprintf('Property name "%s" may not be empty.', $value)
            );
        }

        $this->value = $value;
        $this->locale = $locale;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toSerializableString(): string
    {
        return serialize(
            [
                'content' => $this->value,
                'locale' => $this->locale->toString(),
            ]
        );
    }

    /**
     * @return string
     * @deprecated todo move to object?
     */
    public function locale(): string
    {
        return $this->locale->toString();
    }

    public static function fromLocalizedString(string $value, string $locale): self
    {
        return new self($value, TranslationLocale::fromString($locale));
    }

    public static function fromSerializedString(string $value): self
    {
        $data = unserialize($value);

        return self::fromLocalizedString((string) $data['content'], $data['locale']);
    }

    public static function random(): self
    {
        return self::fromLocalizedString(\uniqid('property-'), 'en');
    }
}
