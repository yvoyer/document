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

    public function onUndefinedTranslation(string $field, array $map, string $locale): string
    {
        return $this->defaultValue;
    }
}
