<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use DateTimeInterface;
use InvalidArgumentException;
use RuntimeException;
use function array_merge;
use function count;
use function date;
use function date_create;
use function date_parse_from_format;
use function implode;
use function sprintf;

final class DateParser
{
    /**
     * @var DateTimeInterface|false
     */
    private $value;

    /**
     * @var string
     */
    private $format;

    /**
     * @param string $format
     * @param DateTimeInterface|false $value
     */
    private function __construct(string $format, $value)
    {
        $this->format = $format;
        $this->value = $value;
    }

    public function toString(): string
    {
        if ($this->isValid()) {
            return $this->toDateTime()->format($this->format);
        }

        return 'false';
    }

    public function diff(DateTimeInterface $date, string $diffFormat): int
    {
        if (! $this->value instanceof DateTimeInterface) {
            return 0;
        }

        return (int) $date->diff($this->value)->format($diffFormat);
    }

    public function isValid(): bool
    {
        return $this->value instanceof DateTimeInterface;
    }

    public function toDateTime(): DateTimeInterface
    {
        if (!$this->value instanceof DateTimeInterface) {
            throw new RuntimeException('The date is invalid.');
        }

        return $this->value;
    }

    public static function assertValidFormat(string $format): void
    {
        $info = date_parse_from_format($format, date($format));
        if (count($info['errors']) > 0 || count($info['warnings']) > 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Date format "%s" is not supported. Errors: "%s".',
                    $format,
                    implode(', ', array_merge($info['errors'], $info['warnings']))
                )
            );
        }
    }

    public static function fromString(string $value): self
    {
        return new self(DATE_ATOM, date_create($value));
    }
}
