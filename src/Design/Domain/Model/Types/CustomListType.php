<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\Design\Domain\Exception\EmptyAllowedOptions;
use Star\Component\Document\Design\Domain\Exception\InvalidPropertyValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\PropertyValue;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\Design\Domain\Model\Values\ListValue;

final class CustomListType implements PropertyType
{
    /**
     * @var string[]|int[]|float[]
     */
    private $allowed;

    /**
     * @param float[]|int[]|string[] $allowed
     */
    public function __construct(array $allowed)
    {
        if (empty($allowed)) {
            throw new EmptyAllowedOptions(
                'Custom list properties must receive at least one option, none given.'
            );
        }
        $this->allowed = $allowed;
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function isValid($value): bool
    {
        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $keyValue) {
            if (! is_string($keyValue) && ! is_numeric($keyValue)) {
                return false;
            }

            if (! isset($this->allowed[$keyValue])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     *
     * @return PropertyValue
     * @throws InvalidPropertyValue
     */
    public function createValue(string $propertyName, $rawValue): PropertyValue
    {
        $value = $rawValue;
        if (is_string($rawValue)) {
            if (empty($value)) {
                $value = [];
            } else {
                $value = explode(';', $value);
            }
        }

        if (! $this->isValid($value)) {
            throw new InvalidPropertyValue(
                sprintf(
                    'The property "%s" only accepts an array made of the following values: "%s", "%s" given.',
                    $propertyName,
                    implode(';', array_keys($this->allowed)),
                    InvalidPropertyValue::getValueType($rawValue)
                )
            );
        }

        return new ListValue(
            $propertyName,
            ...array_map(
                function ($key_value) use ($propertyName) {
                    return new ListOptionValue($propertyName, $key_value, (string) $this->allowed[$key_value]);
                },
                $value
            )
        );
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return 'custom-list';
    }
}
