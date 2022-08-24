<?php declare(strict_types=1);

namespace Star\Component\Document\Audit\Domain\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Star\Component\DomainEvent\Serialization\SerializableAttribute;

final class AuditDateTime implements SerializableAttribute
{
    private DateTimeInterface $date;

    private function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public function toDateFormat(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function toDateTimeFormat(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }

    public function toSerializableString(): string
    {
        return $this->toDateTimeFormat();
    }

    public static function fromString(string $date): self
    {
        return self::fromDateTime(new DateTimeImmutable($date));
    }

    public static function fromDateTime(DateTimeInterface $date): self
    {
        return new self($date);
    }

    public static function fromNow(): self
    {
        return self::fromDateTime(new DateTimeImmutable());
    }
}
