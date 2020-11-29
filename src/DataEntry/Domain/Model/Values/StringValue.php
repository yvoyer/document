<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function mb_strlen;
use function sprintf;

final class StringValue implements RecordValue, CanBeTypeCastToString
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        Assertion::notEmpty($value, 'String value "%s" is empty, but non empty value was expected.');
        $this->value = $value;
    }

    public function count(): int
    {
        return 1;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toTypedString(): string
    {
        return sprintf('string(%s)', $this->toString());
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function isList(): bool
    {
        return false;
    }

    public static function fromString(string $value): RecordValue
    {
        if (mb_strlen($value) === 0) {
            return new EmptyValue();
        }

        if ($value === '0') {
            return IntegerValue::fromString($value);
        }

        return new self($value);
    }

    public static function fromInt(int $value): RecordValue
    {
        return self::fromString((string) $value);
    }

    public static function fromFloat(float $value): RecordValue
    {
        return self::fromString((string) $value);
    }
}
