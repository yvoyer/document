<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function count;
use function explode;
use function mb_strlen;
use function pow;
use function sprintf;
use function str_replace;
use function strval;

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

    private function __construct(int $value, int $decimal)
    {
        $this->value = $value;
        $this->decimal = $decimal;
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
        return strval($this->value / pow(10, $this->decimal));
    }

    public function toTypedString(): string
    {
        return sprintf('float(%s)', $this->toString());
    }

    /**
     * @param string $value A float value ie. "12.34"
     *
     * @return RecordValue
     */
    public static function fromString(string $value): RecordValue
    {
        $parts = explode('.', $value);
        $decimal = 0;
        if (count($parts) === 2) {
            $decimal = mb_strlen($parts[1]);
        }

        return new self(
            (int) str_replace([' ', '.'], '', $value),
            (int) $decimal
        );
    }

    public static function fromInt(int $round, int $decimal): RecordValue
    {
        return new self($round, $decimal);
    }

    public static function fromFloat(float $value): RecordValue
    {
        return self::fromString(strval($value));
    }
}
