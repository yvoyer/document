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

    public function toJson(): string
    {
        return (string) \json_encode($this->errors);
    }

    public function count(): int
    {
        return \count($this->errors);
    }
}
