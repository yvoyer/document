<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use DateTimeInterface;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\DateValue;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;

final class DateType implements PropertyType
{
    public function validateRawValue(string $propertyName, $rawValue): ErrorList
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

    /**
     * @param string $propertyName
     * @param mixed $rawValue
     * @return RecordValue
     */
    public function createValue(string $propertyName, $rawValue): RecordValue
    {
        if ($rawValue instanceof DateTimeInterface) {
            return DateValue::fromDateTime($rawValue);
        }

        if (\is_string($rawValue) && !\is_numeric($rawValue)) {
            if (empty($rawValue)) {
                return new EmptyValue();
            }

            if (\strtotime($rawValue) > 0) {
                return StringValue::fromString($rawValue);
            }
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
