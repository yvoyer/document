<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\Types\EmptyValue;

final class StringValue implements RecordValue
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        Assertion::notEmpty($value);
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
        return $this->value;
    }

    public static function fromString(string $value): RecordValue
    {
        if(\mb_strlen($value) === 0) {
            return new EmptyValue();
        }

        return new self($value);
    }
}
