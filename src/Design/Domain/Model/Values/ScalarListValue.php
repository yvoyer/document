<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class ScalarListValue implements RecordValue
{
    const SEPARATOR = ';';

    /**
     * @var int[]
     */
    private $values = [];

    public function __construct(int $first, int ...$others)
    {
        $this->values = \array_merge([$first], $others);
    }

    public function count(): int
    {
        return \count($this->values);
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function toString(): string
    {
        return \implode(self::SEPARATOR, $this->values);
    }

    public function getType(): string
    {
        return \sprintf('list(%s)', \json_encode($this->values));
    }

    /**
     * @param string[]|int[] $values
     * @return ScalarListValue
     */
    public static function fromArray(array $values): self
    {
        Assertion::notEmpty($values, 'List of scalars is empty, but "int[] | string[]" was expected.');
        $intValues = \array_map(
            function ($value) {
                Assertion::integerish($value);
                return (int) $value;
            },
            $values
        );

        return new self(...$intValues);
    }

    public static function withElements(int $elements): self
    {
        Assertion::greaterThan($elements, 0, 'Number of options "%s" is not greater than "%s".');
        return self::fromArray(\range(1, $elements));
    }
}
