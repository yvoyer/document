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

    public function hasProperty(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitProperty(PropertyDefinition $definition): void
    {
        $name = $definition->getName();
        $this->properties[$name->toString()] = $definition;
    }

    public function visitEnded(array $properties): void
    {
    }
}
