<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model\Strategy;

use Star\Component\Document\Translation\Domain\Model\FallbackStrategy;
use function sprintf;

final class ThrowExceptionWhenNotDefined implements FallbackStrategy
{
    public function onUndefinedTranslation(string $field, array $map, string $locale): string
    {
        throw new MissingLocaleMap(
            sprintf(
                'No translation map for field "%s" could be found for locale "%s".',
                $field,
                $locale
            )
        );
    }
}
