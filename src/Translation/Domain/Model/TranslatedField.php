<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model;

use function array_key_exists;

final class TranslatedField
{
    private string $field;

    /**
     * @var string[] indexed by locale
     */
    private array $localizedMap;

    private FallbackStrategy $strategy;

    protected function __construct(
        string $field,
        array $localizedMap,
        FallbackStrategy $strategy
    ) {
        $this->field = $field;
        $this->localizedMap = $localizedMap;
        $this->strategy = $strategy;
    }

    final public function toTranslatedString(string $locale): string
    {
        if (!array_key_exists($locale, $this->localizedMap)) {
            return $this->strategy->onUndefinedTranslation($this->field, $this->localizedMap, $locale);
        }

        return $this->localizedMap[$locale];
    }

    final public static function withSingleTranslation(
        string $field,
        string $content,
        string $locale,
        FallbackStrategy $strategy
    ): TranslatedField {
        return self::withMultipleTranslation($field, [$locale => $content], $strategy);
    }

    final public static function withMultipleTranslation(
        string $field,
        array $localeMap,
        FallbackStrategy $strategy
    ): TranslatedField {
        return new self($field, $localeMap, $strategy);
    }
}
