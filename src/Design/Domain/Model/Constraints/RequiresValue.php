<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\Design\Domain\Exception\EmptyRequiredValue;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class RequiresValue implements PropertyConstraint
{
    public function validate(PropertyDefinition $definition, $value): void
    {
        if (empty($value)) {
            throw new EmptyRequiredValue(
                sprintf(
                    'Property named "%s" is required, but empty value given.',
                    $definition->getName()->toString()
                )
            );
        }
    }
}
