<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model;

use function sprintf;

final class TranslationLocale
{
    private string $locale;

    private function __construct(string $locale)
    {
        if (mb_strlen($locale) === 0) {
            throw new InvalidTranslationLocale(
                sprintf('The locale "%s" cannot be empty.', $locale)
            );
        }

        $this->locale = $locale;
    }

    final public function toString(): string
    {
        return $this->locale;
    }

    public static function fromString(string $locale): self
    {
        return new self($locale);
    }
}
