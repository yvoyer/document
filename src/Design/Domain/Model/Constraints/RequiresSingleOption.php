<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\Design\Domain\Exception\TooManyValues;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class RequiresSingleOption implements PropertyConstraint
{
    public function validate(PropertyDefinition $definition, $value): void
    {
        if (count($value) > 1) {
            throw new TooManyValues(
                sprintf(
                    'Property named "%s" requires maximum one option, "%s" given.',
                    $definition->getName()->toString(),
                    json_encode($value)
                )
            );
        }
    }
}
