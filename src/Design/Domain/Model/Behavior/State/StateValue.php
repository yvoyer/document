<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Behavior\State;

use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function sprintf;

final class StateValue implements RecordValue
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toTypedString(): string
    {
        return sprintf('state(%s)', $this->value);
    }

    public function count(): int
    {
        throw new RuntimeException('Method ' . __METHOD__ . ' not implemented yet.');
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
        return new self($value);
    }
}
