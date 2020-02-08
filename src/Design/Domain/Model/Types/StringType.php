<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Values\StringValue;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class StringType implements PropertyType
{
    public function validateRawValue(string $propertyName, $rawValue): ErrorList
    {
        $errors = new ErrorList();
        if (!\is_string($rawValue)) {
            $errors->addError(
                $propertyName,
                'en',
                \sprintf('Value "%s" of property "%s" must be a string.', \getType($rawValue), $propertyName)
            );
        }

        return $errors;
    }

    public function createValue(string $propertyName, $rawValue): RecordValue
    {
        if ($this->validateRawValue($propertyName, $rawValue)->hasErrors()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, 'string', $rawValue);
        }

        return StringValue::fromString($rawValue);
    }

    public function toString(): string
    {
        return 'string';
    }
}
