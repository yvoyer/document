<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function sprintf;
use function strval;

final class IntegerValue implements RecordValue, CanBeTypeCastToString
{
    /**
     * @var int
     */
    private $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function isList(): bool
    {
        return false;
    }

    public function count(): int
    {
        return 1;
    }

    public function toString(): string
    {
        return strval($this->value);
    }

    public function toTypedString(): string
    {
        return sprintf('int(%s)', $this->value);
    }

    public static function fromString(string $value): RecordValue
    {
        Assertion::integerish($value);
        return self::fromInt((int) $value);
    }

    public static function fromInt(int $value): RecordValue
    {
        return new self($value);
    }
}
