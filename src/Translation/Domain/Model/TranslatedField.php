<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model;

use Star\Component\Document\Translation\Domain\Model\Strategy\AlwaysThrowExceptions;
use function array_key_exists;
use function mb_strlen;
use function trim;

final class TranslatedField
{
    private string $field;

    /**
     * @var string[] indexed by locale
     */
    private array $localizedMap;

    private TranslationLocale $defaultLocale;

    private FallbackStrategy $strategy;

    protected function __construct(
        string $field,
        array $localizedMap,
        TranslationLocale $defaultLocale,
        FallbackStrategy $strategy
    ) {
        $this->field = $field;
        $this->defaultLocale = $defaultLocale;
        $this->strategy = $strategy;
        $this->localizedMap = [];
        foreach ($localizedMap as $locale => $content) {
            $content = trim($content);
            if (mb_strlen($content) === 0) {
                if ($this->defaultLocale->toString() === $locale) {
                    $content = $this->strategy->whenEmptyContentForDefaultLocale(
                        $this->field,
                        $this->localizedMap,
                        $this->defaultLocale->toString()
                    );
                } else {
                    $content = $this->strategy->whenEmptyContentOnCreate(
                        $this->field,
                        $locale,
                        $this->defaultLocale->toString()
                    );
                }
            }

            $this->localizedMap[$locale] = $content;
        }
    }

    final public function toTranslatedString(string $locale): string
    {
        if (!array_key_exists($locale, $this->localizedMap)) {
            return $this->strategy->whenUndefinedLocaleMap(
                $this->field,
                $this->localizedMap,
                $locale,
                $this->defaultLocale->toString()
            );
        }

        return $this->localizedMap[$locale];
    }

    public function updateLocalizedValue(string $content, string $locale): self
    {
        $content = trim($content);
        if (mb_strlen($content) === 0) {
            if ($this->defaultLocale->toString() === $locale) {
                $content = $this->strategy->whenEmptyContentForDefaultLocale(
                    $this->field,
                    $this->localizedMap,
                    $this->defaultLocale->toString()
                );
            } else {
                $content = $this->strategy->whenEmptyContentOnUpdate(
                    $this->field,
                    $this->localizedMap,
                    $locale,
                    $this->defaultLocale->toString()
                );
            }
        }

        $map = $this->localizedMap;
        $map[$locale] = $content;

        return new self($this->field, $map, $this->defaultLocale, $this->strategy);
    }

    final public static function forDefaultLocale(
        string $field,
        string $content,
        string $defaultLocale,
        FallbackStrategy $strategy = null
    ): TranslatedField {
        if (!$strategy) {
            $strategy = new AlwaysThrowExceptions();
        }

        return new self(
            $field,
            [
                $defaultLocale => $content,
            ],
            TranslationLocale::fromString($defaultLocale),
            $strategy
        );
    }
}
