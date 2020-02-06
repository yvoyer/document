<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class NumberValue implements RecordValue
{
    /**
     * @var int
     */
    private $value;

    private function __construct(int $value)
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
        return strval($this->value);
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
