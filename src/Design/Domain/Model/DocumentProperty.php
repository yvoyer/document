<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

final class DocumentProperty implements ReadOnlyProperty
{
    /**
     * @var DocumentDesigner
     */
    private $document;

    /**
     * @var PropertyDefinition
     */
    private $definition;

    public function __construct(DocumentDesigner $document, PropertyDefinition $definition)
    {
        $this->document = $document;
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
        $visitor->visitProperty($this->getDefinition());
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
