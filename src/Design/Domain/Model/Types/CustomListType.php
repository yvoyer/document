<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Assert\Assertion;
use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\ListOptionValue;
use Star\Component\Document\Design\Domain\Model\Values\OptionListValue;

final class CustomListType implements PropertyType
{
    /**
     * @var OptionListValue
     */
    private $allowed;

    public function __construct(OptionListValue $allowedOptions)
    {
        $this->allowed = $allowedOptions;
    }

    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($rawValue->isEmpty()) {
            return new EmptyValue();
        }

        $values = \explode(RecordValue::LIST_SEPARATOR, $rawValue->toString());
        foreach ($values as $id) {
            Assertion::integerish($id);
            if (! $this->allowed->isAllowed((int) $id)) {
                throw new InvalidPropertyValue(
                    \sprintf(
                        'The property "%s" only accepts an array made of the following values: "%s", "%s" given.',
                        $propertyName,
                        $this->allowed->getType(),
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

    public function toData(): TypeData
    {
        return new TypeData(
            self::class,
            \array_map(
                function (int $id): array {
                    return $this->allowed->getOption($id)->toArray();
                },
                \explode(RecordValue::LIST_SEPARATOR, $this->allowed->toString())
            )
        );
    }

    public static function fromData(array $arguments): PropertyType
    {
        return new self(
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
