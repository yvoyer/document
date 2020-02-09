<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use DateTimeInterface;
use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class DateType implements PropertyType
{
    private function validateRawValue(string $propertyName, RawValue $rawValue): ErrorList
    {
        $errors = new ErrorList();
        if (\is_string($rawValue) && \strtotime($rawValue) === false) {
            $errors->addError(
                $propertyName,
                'en',
                \sprintf('Date value "%s" is expected to be a string of date format or empty.', $rawValue)
            );
        }

        return $errors;
    }

    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($rawValue->isEmpty()) {
            return new EmptyValue();
        }

        if ($rawValue->isDate()) {
            return DateValue::fromString($rawValue->toString());
        }

        if (\strtotime($rawValue->toString()) > 0) {
            return StringValue::fromString($rawValue);
        }


        throw InvalidPropertyValue::invalidValueForType($propertyName, 'date', $rawValue);
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
