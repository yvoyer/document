<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Validation;

final class ErrorList implements \Countable
{
    /**
     * @var string[][][]
     */
    private $errors = [];

    public function addError(string $propertyName, string $locale, string $message): void
    {
        $this->errors[$propertyName][$locale][] = $message;
    }

    public function hasErrors(): bool
    {
        return \count($this->errors) > 0;
    }

    /**
     * @param string $propertyName
     * @param string $locale
     * @return string[]
     */
    public function getErrorsForProperty(string $propertyName, string $locale): array
    {
        return $this->errors[$propertyName][$locale];
    }

    private function supportedProperties(): array
    {
        return \array_keys($this->errors);
    }

    private function supportedLocales(): array
    {
        $locales = [];
        foreach ($this->errors as $property => $locales) {
            foreach ($locales as $locale => $messages) {
                $locales[] = $locale;
            }
        }

        return \array_unique($locales);
    }

    public function getLocalizedMessages(string $locale): array
    {
        $messages = [];
        foreach ($this->errors as $property => $localizedMessages) {
            foreach ($localizedMessages as $_locale => $message) {
                if ($_locale === $locale) {
                    $messages = \array_merge($messages, $message);
                }
            }
        }

        return $messages;
    }

    public function count(): int
    {
        return \count($this->errors);
    }
}
