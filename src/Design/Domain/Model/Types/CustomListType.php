<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\Design\Domain\Model\Values\ListValue;

final class CustomListType implements PropertyType
{
    private const SEPARATOR = ';';
    /**
     * @var ListOptionValue[]
     */
    private $allowed = [];

    public function __construct(ListOptionValue $first, ListOptionValue ...$others)
    {
        /**
         * @var ListOptionValue[] $allowed
         */
        $allowed = \array_merge([$first], $others);
        foreach ($allowed as $option) {
            $this->allowed[$option->getId()] = $option;
        }
    }

    /**
     * @param mixed $values
     * @return bool
     */
    private function isValid($values): bool
    {
        if (! \is_array($values)) {
            return false;
        }

        foreach ($values as $id) {
            if (\is_string($id) || \is_int($id)) {
                if (\array_key_exists($id, $this->allowed)) {
                    continue;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @param string $propertyName
     * @param string|string[]|int[] $rawValue
     * @return PropertyValue
     */
    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        $convertedValue = $rawValue;
        if (\is_string($rawValue)) {
            $convertedValue = \explode(self::SEPARATOR, $rawValue);
        }

        if ($rawValue === '') {
            $convertedValue = [];
        }

        if (! $this->isValid($convertedValue)) {
            throw new InvalidPropertyValue(
                \sprintf(
                    'The property "%s" only accepts an array made of the following values: "%s", "%s" given.',
                    $propertyName,
                    \implode(
                        self::SEPARATOR,
                        \array_map(
                            function (ListOptionValue $value): int {
                                return $value->getId();
                            },
                            $this->allowed
                        )
                    ),
                    InvalidPropertyValue::getValueType($rawValue)
                )
            );
        }

        return new ListValue(
            $propertyName,
            ...\array_map(
                function (int $key_value) {
                    return $this->allowed[$key_value];
                },
                (array) $convertedValue
            )
        );
    }

    public function toString(): string
    {
        return 'custom-list';
    }
}
