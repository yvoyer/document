<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function array_map;
use function array_merge;
use function count;
use function explode;
use function implode;
use function mb_strlen;
use function range;
use function sprintf;

final class ArrayOfInteger implements RecordValue
{
    /**
     * @var int[]
     */
    private $values;

    private function __construct(int $first, int ...$others)
    {
        $this->values = array_merge([$first], $others);
    }

    public function count(): int
    {
        return count($this->values);
    }

    public function isList(): bool
    {
        return true;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function toString(): string
    {
        return implode(self::LIST_SEPARATOR, $this->values);
    }

    public function toTypedString(): string
    {
        return sprintf('list(%s)', $this->toString());
    }

    public static function withValues(int $value, int ...$other): self
    {
        return new self(...array_merge([$value], $other));
    }

    public static function withElements(int $elements): self
    {
        Assertion::greaterThan($elements, 0, 'Number of scalar "%s" is not greater than "%s".');
        return self::withValues(...range(1, $elements));
    }

    public static function fromString(string $value): RecordValue
    {
        if (mb_strlen($value) === 0) {
            return new EmptyValue();
        }

        return self::withValues(
            ...array_map(
                function ($item): int {
                    Assertion::integerish($item);
                    return (int) $item;
                },
                explode(self::LIST_SEPARATOR, $value)
            )
        );
    }
}
