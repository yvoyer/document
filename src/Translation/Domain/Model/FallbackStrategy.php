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
     * @param string $locale
     * @return string
     */
    public function onUndefinedTranslation(string $field, array $map, string $locale): string;
}
