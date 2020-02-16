<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\Design\Domain\Model\Values\OptionListValue;

final class CustomListType implements PropertyType
{
    /**
     * @var string
     */
    private $typeName;

    /**
     * @var OptionListValue
     */
    private $allowed;

    public function __construct(string $typeName, OptionListValue $allowedOptions)
    {
        $this->typeName = $typeName;
        $this->allowed = $allowedOptions;
    }

    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($rawValue->isEmpty()) {
            return new EmptyValue();
        }

        if (! $rawValue->isString() && ! $rawValue->isInt() && ! $rawValue->isArray()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, $this->toString(), $rawValue);
        }

        $values = \explode(RecordValue::LIST_SEPARATOR, $rawValue->toString());
        foreach ($values as $id) {
            if (! $this->allowed->isAllowed((int) $id)) {
                throw new InvalidPropertyValue(
                    \sprintf(
                        'The property "%s" only accepts an array made of the following values: "%s", "%s" given.',
                        $propertyName,
                        $this->allowed->toString(),
                        $rawValue->getType()
                    )
                );
            }
        }

        return OptionListValue::fromArray(
            \array_map(
                function (int $key_value) {
                    return $this->allowed->getOption($key_value);
                },
                (array) \explode(RecordValue::LIST_SEPARATOR, $rawValue->toString())
            )
        );
    }

    public function createDefaultValue(): RecordValue
    {
        return new EmptyValue();
    }

    public function toString(): string
    {
        return $this->typeName;
    }

    public function toData(): TypeData
    {
        return new TypeData(
            self::class,
            \array_merge(
                ['type-name' => $this->typeName],
                \array_map(
                    function (int $id): array {
                        return $this->allowed->getOption($id)->toArray();
                    },
                    \explode(RecordValue::LIST_SEPARATOR, $this->allowed->toString())
                )
            )
        );
    }

    /**
     * @param mixed[] $arguments
     * @return PropertyType
     */
    public static function fromData(array $arguments): PropertyType
    {
        $typeName = $arguments['type-name'];
        unset($arguments['type-name']);

        return new self(
            $typeName,
            OptionListValue::fromArray(
                \array_map(
                    function (array $row) {
                        return new ListOptionValue($row['id'], $row['value'], $row['label']);
                    },
                    $arguments
                )
            )
        );
    }
}
