<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\Constraint;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use function sprintf;

final class RequiresValue implements PropertyConstraint
{
    public function validate(string $propertyName, RecordValue $value, ErrorList $errors): void
    {
        if ($value->isEmpty()) {
            $errors->addError(
                $propertyName,
                'en',
                sprintf(
                    'Property named "%s" is required, but "%s" given.',
                    $propertyName,
                    $value->toTypedString()
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class);
    }

    public static function fromData(ConstraintData $data): Constraint
    {
        return new static();
    }
}
