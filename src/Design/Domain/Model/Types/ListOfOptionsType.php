<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use RuntimeException;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Values\ArrayOfInteger;
use Star\Component\Document\DataEntry\Domain\Model\Values\OptionListValue;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use function array_map;
use function explode;
use function sprintf;

final class ListOfOptionsType implements PropertyType
{
    private string $typeName;
    private OptionListValue $allowed;

    public function __construct(string $typeName, OptionListValue $allowedOptions)
    {
        $this->typeName = $typeName;
        $this->allowed = $allowedOptions;
    }

    public function toWriteFormat(RecordValue $value): RecordValue
    {
        return $value;
    }

    public function toReadFormat(RecordValue $value): RecordValue
    {
        if ($value->isEmpty()) {
            return $value;
        }

        $values = explode(RecordValue::LIST_SEPARATOR, $value->toString());
        foreach ($values as $id) {
            if (! $this->allowed->idIsAllowed((int) $id)) {
                throw new InvalidPropertyValue(
                    sprintf(
                        'The list "%s" only accepts an array made of the following values: "%s", "%s" given.',
                        $this->typeName,
                        $this->allowed->toString(),
                        $value->toTypedString()
                    )
                );
            }
        }

        return OptionListValue::fromArray(
            array_map(
                function (int $key_value) {
                    return $this->allowed->getOption($key_value);
                },
                $values
            )
        );
    }

    public function supportsType(RecordValue $value): bool
    {
        return $value instanceof ArrayOfInteger || $value->isEmpty();
    }

    public function supportsValue(RecordValue $value): bool
    {
        if ($value->isEmpty()) {
            return true;
        }

        if (! $value instanceof ArrayOfInteger) {
            return false;
        }

        $values = explode(RecordValue::LIST_SEPARATOR, $value->toString());
        foreach ($values as $id) {
            if (! $this->allowed->idIsAllowed((int) $id)) {
                return false;
            }
        }

        return true;
    }

    public function generateExceptionForNotSupportedTypeForValue(
        PropertyCode $property,
        RecordValue $value
    ): NotSupportedTypeForValue {
        return new NotSupportedTypeForValue(
            $property,
            $value,
            $this
        );
    }

    public function generateExceptionForNotSupportedValue(
        PropertyCode $property,
        RecordValue $value
    ): InvalidPropertyValue {
        $values = explode(RecordValue::LIST_SEPARATOR, $value->toString());
        foreach ($values as $id) {
            if (! $this->allowed->idIsAllowed((int) $id)) {
                return new InvalidPropertyValue(
                    sprintf(
                        'Value for property "%s" must contain valid option ids. Supporting: "%s", given "%s".',
                        $property->toString(),
                        $this->allowed->toString(),
                        $value->toTypedString()
                    )
                );
            }
        }

        return InvalidPropertyValue::invalidValueForType($property, $value, $this);
    }

    public function doBehavior(string $property, RecordValue $value): RecordValue
    {
        throw new RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function toHumanReadableString(): string
    {
        return $this->typeName;
    }

    public function toData(): TypeData
    {
        return new TypeData(
            self::class,
            [
                'type-name' => $this->typeName,
                'value' => $this->allowed->toString(),
            ]
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

        return new ListOfOptionsType(
            $typeName,
            OptionListValue::fromJson($arguments['value'])
        );
    }
}
