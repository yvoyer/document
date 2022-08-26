<?php declare(strict_types=1);

namespace Star\Component\Document\Translation\Domain\Model\Strategy;

use Star\Component\Document\Translation\Domain\Model\FallbackStrategy;

final class ReturnDefaultValue implements FallbackStrategy
{
    private string $defaultValue;

    public function __construct(string $defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    public function whenUndefinedLocaleMap(
        string $field,
        array $map,
        string $locale,
        string $defaultLocale
    ): string {
        return $this->defaultValue;
    }

    public function whenEmptyContentOnUpdate(
        string $field,
        array $map,
        string $locale,
        string $defaultLocale
    ): string {
        return $this->defaultValue;
    }

    public function whenEmptyContentOnCreate(
        string $field,
        string $locale,
        string $defaultLocale
    ): string {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function whenEmptyContentForDefaultLocale(
        string $field,
        array $map,
        string $defaultLocale
    ): string {
        return $this->defaultValue;
    }
}
