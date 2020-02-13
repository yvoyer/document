<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class DateValue implements RecordValue
{
    /**
     * @var \DateTimeInterface
     */
    private $value;

    private function __construct(\DateTimeInterface $value)
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

    public function toString(): string
    {
        return $this->value->format('Y-m-d');
    }

    public function toTypedString(): string
    {
        return \sprintf('date(%s)', $this->toString());
    }

    public function toReadableString(): string
    {
        return $this->toString();
    }

    public static function fromString(string $date): RecordValue
    {
        if (\mb_strlen($date) === 0) {
            return new EmptyValue();
        }

        $providedDate = DateParser::fromString($date);
        if (!$providedDate->isValid()) {
            return StringValue::fromString($date);
        }

        return self::fromDateTime($providedDate->toDateTime());
    }

    public static function fromDateTime(\DateTimeInterface $date): RecordValue
    {
        return new self($date);
    }
}
