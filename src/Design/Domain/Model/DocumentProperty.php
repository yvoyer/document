<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\Schema\PropertyDefinition;

final class DocumentProperty implements ReadOnlyProperty
{
    /**
     * @var PropertyDefinition
     */
    private $definition;

    public function __construct(PropertyDefinition $definition)
    {
        $this->definition = $definition;
    }

    public function addConstraint(string $name, PropertyConstraint $constraint): void
    {
        $this->definition = $this->definition->addConstraint($name, $constraint);
    }

    public function removeConstraint(string $name): void
    {
        $this->definition = $this->definition->removeConstraint($name);
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        $this->definition->acceptDocumentVisitor($visitor);
    }

    public function matchName(PropertyName $name): bool
    {
        return $name->matchName($this->definition->getName());
    }

    public function getDefinition(): PropertyDefinition
    {
        return $this->definition;
    }
}
