<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class All implements PropertyConstraint
{
    /**
     * @var PropertyConstraint[]
     */
    private $constraints;

    public function __construct(PropertyConstraint $first, PropertyConstraint ...$constraints)
    {
        $this->constraints = \array_merge([$first], $constraints);
    }

    public function validate(PropertyDefinition $definition, $value): void
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($definition, $value);
        }
    }
}
