<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Types;

use Star\Component\Document\DataEntry\Domain\Model\RawValue;
use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Values\BooleanValue;
use Star\Component\Document\Design\Domain\Model\Values\EmptyValue;

final class BooleanType implements PropertyType
{
    private function validateRawValue(string $propertyName, RawValue $rawValue): ErrorList
    {
        $errors = new ErrorList();
        if (! $rawValue->isBool()) {
            $errors->addError(
                $propertyName,
                'en',
                \sprintf(
                    'Boolean property "%s" only accept boolish values ' .
                    '(true, false, "true", "false") values, "%s" given.',
                    $propertyName,
                    \gettype($rawValue)
                )
            );
        }

        return $errors;
    }

    public function createValue(string $propertyName, RawValue $rawValue): RecordValue
    {
        if ($rawValue->isEmpty()) {
            return new EmptyValue();
        }

        if ($this->validateRawValue($propertyName, $rawValue)->hasErrors()) {
            throw InvalidPropertyValue::invalidValueForType($propertyName, $this->toString(), $rawValue);
        }

        return BooleanValue::fromString($rawValue->toString());
    }

    public function toData(): TypeData
    {
        return new TypeData(self::class);
    }

    public function toString(): string
    {
        return 'boolean';
    }

    /**
     * @param mixed[] $arguments
     * @return PropertyType
     */
    public static function fromData(array $arguments): PropertyType
    {
        return new self();
    }
}
