<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class FloatValue implements RecordValue, CanBeTypeCastToString
{
    /**
     * @var int The complete int value of the float ie. "12.34" => 1234
     */
    private $value;

    /**
     * @var int The number of decimal after the dot ie. "12.34" => 2 (which is 34)
     */
    private $decimal;

    public function __construct(int $value, int $decimal)
    {
        $this->value = $value;
        $this->decimal = $decimal;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function count(): int
    {
        return 1;
    }

    public function toString(): string
    {
        return (string) \substr_replace((string) $this->value, '.', - $this->decimal, 0);
    }

    public function toTypedString(): string
    {
        return \sprintf('float(%s)', $this->toString());
    }

    public function toReadableString(): string
    {
        return $this->toString();
    }

    /**
     * @param string $value A float value ie. "12.34"
     *
     * @return RecordValue
     */
    public static function fromString(string $value): RecordValue
    {
        $parts = \explode('.', $value);

        return new self((int) \str_replace('.', '', $value), \mb_strlen($parts[1]));
    }

    public static function fromFloat(float $value): RecordValue
    {
        return self::fromString(\strval($value));
    }
}
