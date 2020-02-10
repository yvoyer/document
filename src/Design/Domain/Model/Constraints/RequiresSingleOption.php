<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\RecordValue;
use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

final class RequiresSingleOption implements PropertyConstraint
{
    public function validate(string $name, RecordValue $value, ErrorList $errors): void
    {
        if (\count($value) > 1) {// todo change to RequireOptionCount
            $errors->addError(
                $name,
                'en',
                \sprintf(
                    'Property named "%s" allows only one option, "%s" given.',
                    $name,
                    $value->toTypedString()
                )
            );
        }
    }

    public function toData(): ConstraintData
    {
        return new ConstraintData(self::class);
    }

    public static function fromData(ConstraintData $data): PropertyConstraint
    {
        return new self();
    }
}
