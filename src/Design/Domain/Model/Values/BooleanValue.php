<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

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

    public static function trueValue(): RecordValue
    {
        return new self(true);
    }

    public static function falseValue(): RecordValue
    {
        return new self(false);
    }
}
