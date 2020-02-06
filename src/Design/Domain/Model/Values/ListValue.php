<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Values;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;

final class ListValue implements RecordValue
{
    /**
     * @var ListOptionValue[]
     */
    private $values = [];

    public function __construct(ListOptionValue ...$value)
    {
        $this->values = $value;
    }

    public function count(): int
    {
        return \count($this->values);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function toString(): string
    {
        return implode(
            ';',
            \array_map(
                function (ListOptionValue $value) {
                    return $value->getLabel();
                },
                $this->values
            )
        );
    }

    /**
     * @param ListOptionValue[] $value
     * @return RecordValue
     */
    public static function fromArray(array $value): RecordValue
    {
        return new self(...$value);
    }

    public static function withElements(int $elements): RecordValue
    {
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
