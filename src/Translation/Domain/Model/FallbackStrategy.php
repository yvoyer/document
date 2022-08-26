<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model;

/**
 * Strategy configured in a TranslatedField
 * @see TranslatedField
 */
interface FallbackStrategy
{
    /**
     * @param string $field
     * @param string[] $map Map of existing locale translations
     * @param string $locale The locale that is not defined
     * @param string $defaultLocale
     * @return string The value to use for the not mapped locale (if applicable)
     */
    public function whenUndefinedLocaleMap(
        string $field,
        array $map,
        string $locale,
        string $defaultLocale
    ): string;

    /**
     * @param string $field
     * @param string $locale The locale where the content is empty
     * @param string $defaultLocale
     * @return string The value to use for the not mapped locale (if applicable)
     */
    public function whenEmptyContentOnCreate(
        string $field,
        string $locale,
        string $defaultLocale
    ): string;

    /**
     * @param string $field
     * @param string[] $map Map of existing locale translations
     * @param string $locale The locale where the content is empty
     * @param string $defaultLocale
     * @return string The value to use for the not mapped locale (if applicable)
     */
    public function whenEmptyContentOnUpdate(
        string $field,
        array $map,
        string $locale,
        string $defaultLocale
    ): string;

    /**
     * @param string $field
     * @param string[] $map Map of existing locale translations
     * @param string $defaultLocale
     * @return string The value to use for the not mapped locale (if applicable)
     */
    public function whenEmptyContentForDefaultLocale(
        string $field,
        array $map,
        string $defaultLocale
    ): string;
}
