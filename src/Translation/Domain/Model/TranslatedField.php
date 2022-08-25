<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model;

use Star\Component\Document\Translation\Domain\Model\Strategy\ThrowExceptionWhenNotDefined;
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

    public function update(string $value, string $locale): self
    {
        $map = $this->localizedMap;
        $map[$locale] = $value;

        return self::withMultipleTranslation($this->field, $map, $this->strategy);
    }

    final public static function withSingleTranslation(
        string $field,
        string $content,
        string $locale,
        FallbackStrategy $strategy = null
    ): TranslatedField {
        if (!$strategy) {
            $strategy = new ThrowExceptionWhenNotDefined();
        }

        return self::withMultipleTranslation($field, [$locale => $content], $strategy);
    }

    final public static function withMultipleTranslation(
        string $field,
        array $localeMap,
        FallbackStrategy $strategy = null
    ): TranslatedField {
        if (!$strategy) {
            $strategy = new ThrowExceptionWhenNotDefined();
        }

        return new self($field, $localeMap, $strategy);
    }
}
