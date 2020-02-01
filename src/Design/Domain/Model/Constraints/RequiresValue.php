<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class RequiresValue implements PropertyConstraint
{
    public function validate(PropertyName $name, $value, ErrorList $errors): void
    {
        if (empty($value)) {
            $errors->addError(
                $name->toString(),
                'en',
                \sprintf(
                    'Property named "%s" is required, but empty value given.',
                    $name->toString()
                )
            );
        }
    }
}
