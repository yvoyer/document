<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Structure;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyDefinition;

final class PropertyExtractor implements DocumentVisitor
{
    /**
     * @var PropertyDefinition[]
     */
    private $properties = [];

    /**
     * @return PropertyDefinition[]
     */
    public function properties(): array
    {
        return array_values($this->properties);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    /**
     * @param DocumentId $id
     */
    public function visitDocument(DocumentId $id)
    {
    }

    /**
     * @param PropertyDefinition $definition
     */
    public function visitProperty(PropertyDefinition $definition)
    {
        $name = $definition->getName();
        $this->properties[$name->toString()] = $definition;
    }
}
