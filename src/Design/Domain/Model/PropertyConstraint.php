<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface PropertyConstraint
{
    /**
     * @param PropertyDefinition $definition
     * @param mixed $value
     *
     * @throws \LogicException
     */
    public function validate(PropertyDefinition $definition, $value): void;
}
