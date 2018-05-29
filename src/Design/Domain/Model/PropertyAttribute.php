<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface PropertyAttribute
{
    /**
     * @param PropertyDefinition $definition
     */
    public function updateDefinition(PropertyDefinition $definition);
}
