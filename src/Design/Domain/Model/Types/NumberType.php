<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\FloatValue;
use Star\Component\Document\Design\Domain\Model\Values\IntegerValue;

final class NumberType implements PropertyType
{
    private function validateRawValue(string $propertyName, RawValue $rawValue): ErrorList
    {
        $errors = new ErrorList();
        if (!\is_numeric($rawValue)) {
            $errors->addError(
                $propertyName,
                'en',
                \sprintf(
                    'Number property "%s" only accepts numeric values, "%s" given.',
                    $propertyName,
                    \gettype($rawValue)
                )
            );
        }

        return $errors;
    }

    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($this->validateRawValue($propertyName, $rawValue)->hasErrors()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'number', $rawValue);
        }

        if (\is_int($rawValue) || (int) $rawValue == $rawValue) {
            return IntegerValue::fromInt((int) $rawValue);
        }

        return FloatValue::fromString((string) $rawValue);
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public static function fromData(array $arguments): PropertyType
    {
        return new self();
    }
}
