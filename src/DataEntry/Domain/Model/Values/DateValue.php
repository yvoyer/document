<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Throwable;
use function mb_strlen;
use function sprintf;

final class DateValue implements RecordValue
{
    const DEFAULT_FORMAT = 'Y-m-d';

    /**
     * @var DateTimeInterface
     */
    private $value;

    private function __construct(DateTimeInterface $value)
    {
        $this->value = $value;
    }

    public function count(): int
    {
        return 1;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function isList(): bool
    {
        return false;
    }

    public function toString(): string
    {
        return $this->value->format(self::DEFAULT_FORMAT);
    }

    public function toTypedString(): string
    {
        return sprintf('date(%s)', $this->toString());
    }

    public static function fromString(string $value): RecordValue
    {
        if (mb_strlen($value) === 0) {
            return new EmptyValue();
        }

        try {
            return self::fromDateTime(new DateTimeImmutable($value));
        } catch (Throwable $exception) {
            throw new InvalidArgumentException(
                sprintf(
                    'The date value format must be "%s", "%s" given.',
                    self::DEFAULT_FORMAT,
                    $value
                ),
                $exception->getCode(),
                $exception
            );
        }
    }

    public static function fromDateTime(DateTimeInterface $date): RecordValue
    {
        return new self($date);
    }
}
