<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class OptionListValue implements RecordValue
{
    /**
     * @var ListOptionValue[]
     */
    private $values = [];

    private function __construct(ListOptionValue $first, ListOptionValue ...$others)
    {
        \array_map(
            function (ListOptionValue $value) {
                $this->values[$value->getId()] = $value;
            },
            \array_merge([$first], $others)
        );
    }

    public function isAllowed(int $id): bool
    {
        return \array_key_exists($id, $this->values);
    }

    public function getOption(int $id): ListOptionValue
    {
        return $this->values[$id];
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
        return \implode(
            RecordValue::LIST_SEPARATOR,
            \array_map(
                function (ListOptionValue $value) {
                    return $value->getId();
                },
                $this->values
            )
        );
    }

    public function getType(): string
    {
        return \sprintf(
            '[%s]',
            \implode(
                RecordValue::LIST_SEPARATOR,
                \array_map(
                    function (ListOptionValue $value) {
                        return $value->getLabel();
                    },
                    $this->values
                )
            )
        );
    }

    public static function fromArray(array $value): self
    {
        return new self(...$value);
    }

    public static function withElements(int $elements): self
    {
        Assertion::greaterThan($elements, 0);

        return self::fromArray(
            \array_map(
                function (int $element) {
                    return new ListOptionValue($element, 'Option ' . $element, 'Label ' . $element);
                },
                \range(1, $elements)
            )
        );
    }
}
