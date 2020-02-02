<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\DataEntry\Domain\Model\Validation\ErrorList;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;

final class RequiresSingleOption implements PropertyConstraint
{
    public function validate(PropertyName $name, $value, ErrorList $errors): void
    {
        if (\count($value) > 1) {
            $errors->addError(
                $name->toString(),
                'en',
                \sprintf(
                    'Property named "%s" allows only one option, "%s" given.',
                    $name->toString(),
                    \json_encode($value)
                )
            );
        }
    }
}
