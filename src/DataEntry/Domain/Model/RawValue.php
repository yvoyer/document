<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model;

use Assert\Assertion;
use Star\Component\Document\Design\Domain\Model\Values\BooleanValue;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Values\IntegerValue;
use Star\Component\Document\Design\Domain\Model\Values\ObjectValue;
use Star\Component\Document\Design\Domain\Model\Values\ScalarListValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class RawValue
{
    /**
     * @var RecordValue
     */
    private $value;

    private function __construct(RecordValue $value)
    {
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return $this->value->isEmpty();
    }

    public function isBool(): bool
    {
        return $this->value instanceof BooleanValue;
    }

    public function isArray(): bool
    {
        return $this->value instanceof ScalarListValue;
    }

    public function isString(): bool
    {
        return $this->value instanceof StringValue;
    }

    public function isNumeric(): bool
    {
        return $this->isFloat() || $this->isInt();
    }

    public function isFloat(): bool
    {
        return $this->value instanceof FloatValue;
    }

    public function isInt(): bool
    {
        return $this->value instanceof IntegerValue;
    }

    public function isDate(): bool
    {
        return $this->value instanceof DateValue;
    }

    public function isObject(): bool
    {
        return $this->value instanceof ObjectValue;
    }

    public function toString(): string
    {
        return $this->value->toString();
    }

    public function getType(): string
    {
        return $this->value->toTypedString();
    }

    public static function fromBoolean(bool $value): self
    {
        return new self(new BooleanValue($value));
    }

    public static function fromString(string $raw): self
    {
        if ($raw === 'true' || $raw === 'false') {
            return new self(BooleanValue::fromString($raw));
        }

        return new self(StringValue::fromString($raw));
    }

    public static function fromInt(int $value): self
    {
        return new self(IntegerValue::fromInt($value));
    }

    public static function fromFloat(float $value): self
    {
        return new self(FloatValue::fromFloat($value));
    }

    public static function fromDate(\DateTimeInterface $value): self
    {
        return new self(DateValue::fromDateTime($value));
    }

    public static function fromArray(array $value): self
    {
        if (\count($value) === 0) {
            return self::fromEmpty();
        }

        return new self(ScalarListValue::fromArray($value));
    }

    public static function fromObject(object $value): self
    {
        return new self(new ObjectValue($value));
    }

    public static function fromNumeric($value): self
    {
        Assertion::numeric($value);
        if (\is_int($value) || \intval($value) == $value) {
            return self::fromInt((int) $value);
        }

        return self::fromFloat(\floatval($value));
    }

    public static function fromEmpty(): self
    {
        return new self(new EmptyValue());
    }

    /**
     * @param mixed $raw
     * @return RawValue
     */
    public static function fromMixed($raw): self
    {
        if (\is_null($raw)) {
            return self::fromEmpty();
        }

        if (\is_bool($raw)) {
            return self::fromBoolean($raw);
        }

        if (\is_numeric($raw)) {
            return self::fromNumeric($raw);
        }

        if (\is_string($raw)) {
            return self::fromString($raw);
        }

        if (\is_array($raw)) {
            return self::fromArray($raw);
        }

        if ($raw instanceof \DateTimeInterface) {
            return self::fromDate($raw);
        }

        if (\is_object($raw)) {
            return self::fromObject($raw);
        }

        throw new \InvalidArgumentException(
            \sprintf('Raw value "%s" was not supported.', gettype($raw))
        );
    }
}
