<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Constraints;

use Star\Component\Document\Design\Domain\Model\PropertyConstraint;

/**
 * todo check if still useful
 */
interface ConstraintRegistry
{
    /**
     * @param string $alias
     * @param mixed[] $arguments
     * @return PropertyConstraint
     */
    public function createPropertyConstraint(string $alias, array $arguments): PropertyConstraint;
}
