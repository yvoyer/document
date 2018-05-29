<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Definition;

use Star\Component\Document\Design\Domain\Model\PropertyAttribute;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class RequiredProperty implements PropertyAttribute
{
    /**
     * @param PropertyDefinition $definition
     */
    public function updateDefinition(PropertyDefinition $definition)
    {
        $definition->setMandatory(true);
    }
}
