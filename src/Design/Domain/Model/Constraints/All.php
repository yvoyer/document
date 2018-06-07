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

    /**
     * @param PropertyConstraint[] ...$constraints
     */
    public function __construct(PropertyConstraint ...$constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * @param PropertyDefinition $definition
     * @param mixed $value
     *
     * @throws \LogicException
     */
    public function validate(PropertyDefinition $definition, $value)
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($definition, $value);
        }
    }
}
