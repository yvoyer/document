<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Values;

use Assert\Assertion;
use InvalidArgumentException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use function array_map;
use function array_merge;
use function count;
use function implode;
use function json_decode;
use function json_encode;
use function mb_strlen;
use function range;
use function sprintf;

final class OptionListValue implements RecordValue
{
    /**
     * @var ListOptionValue[]
     */
    private $values;

    private function __construct(ListOptionValue $first, ListOptionValue ...$others)
    {
        $this->values = array_merge([$first], $others);
    }

    public function idIsAllowed(int $id): bool
    {
        foreach ($this->values as $value) {
            if ($value->getId() === $id) {
                return true;
            }
        }

        return false;
    }

    public function getOption(int $id): ListOptionValue
    {
        foreach ($this->values as $value) {
            if ($value->getId() === $id) {
                return $value;
            }
        }

        throw new InvalidArgumentException(sprintf('Option with id "%s" could not be found', $id));
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
        return (string) json_encode(
            array_map(
                function (ListOptionValue $value) {
                    return $value->toArray();
                },
                $this->values
            )
        );
    }

    public function toTypedString(): string
    {
        return sprintf(
            'options(%s)',
            implode(
                RecordValue::LIST_SEPARATOR,
                array_map(
                    function (ListOptionValue $value) {
                        return $value->getLabel();
                    },
                    $this->values
                )
            )
        );
    }

    /**
     * @param ListOptionValue[] $values
     * @return OptionListValue
     */
    public static function fromArray(array $values): OptionListValue
    {
        Assertion::notEmpty($values, 'List of options is empty, but "ListOptionValue[]" was expected.');
        return new OptionListValue(...$values);
    }

    public static function withOptionItems(ListOptionValue $first, ListOptionValue ...$others): OptionListValue
    {
        return self::fromArray(array_merge([$first], $others));
    }

    public static function withElements(int $elements): OptionListValue
    {
        Assertion::greaterThan($elements, 0, 'Number of options "%s" is not greater than "%s".');

        return self::fromArray(
            array_map(
                function (int $element) {
                    return new ListOptionValue($element, 'Option ' . $element, 'Label ' . $element);
                },
                range(1, $elements)
            )
        );
    }

    public static function fromJson(string $json): OptionListValue
    {
        Assertion::isJsonString($json);
        $items = array_map(
            function (array $row) {
                return ListOptionValue::fromArray($row);
            },
            json_decode($json, true)
        );

        return self::fromArray($items);
    }

    public static function fromString(string $value): RecordValue
    {
        if (mb_strlen($value) === 0) {
            return new EmptyValue();
        }

        return self::fromJson($value);
    }
}
