<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class BooleanValue implements RecordValue
{
    /**
     * @var bool
     */
    private $value;

    public function __construct(bool $value)
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
        return ($this->value) ? 'true' : 'false';
    }

    public function toTypedString(): string
    {
        return \sprintf('boolean(%s)', $this->toString());
    }

    public function toReadableString(): string
    {
        return $this->toString();
    }

    public static function trueValue(): RecordValue
    {
        return new self(true);
    }

    public static function falseValue(): RecordValue
    {
        return new self(false);
    }

    public static function fromString(string $value): RecordValue
    {
        Assertion::inArray($value, ['true', 'false']);

        return new self(($value === 'true'));
    }
}
