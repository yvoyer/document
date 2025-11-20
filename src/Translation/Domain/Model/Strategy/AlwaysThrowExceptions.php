<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model\Strategy;

use Star\Component\Document\Translation\Domain\Model\FallbackStrategy;
use function sprintf;

final class AlwaysThrowExceptions implements FallbackStrategy
{
    public function whenUndefinedLocaleMap(
        string $field,
        array $map,
        string $locale,
        string $defaultLocale
    ): string {
        throw new MissingLocaleMap(
            sprintf(
                'No translation map for field "%s" could be found for locale "%s".',
                $field,
                $locale
            )
        );
    }

    public function whenEmptyContentOnCreate(
        string $field,
        string $locale,
        string $defaultLocale): string {
        throw new NotAllowedEmptyTranslation(
            sprintf('Field "%s" is not allowed to be empty for locale "%s".', $field, $locale)
        );
    }

    public function whenEmptyContentOnUpdate(
        string $field,
        array $map,
        string $locale,
        string $defaultLocale
    ): string {
        throw new NotAllowedEmptyTranslation(
            sprintf('Field "%s" is not allowed to be empty for locale "%s".', $field, $locale)
        );
    }

    public function whenEmptyContentForDefaultLocale(
        string $field,
        array $map,
        string $defaultLocale
    ): string {
        throw new NotAllowedEmptyTranslation(
            sprintf('Field "%s" is not allowed to be empty for default locale "%s".', $field, $defaultLocale)
        );
    }
}
