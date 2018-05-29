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

    /**
     * @param DocumentDesigner $document
     * @param PropertyDefinition $definition
     */
    public function __construct(DocumentDesigner $document, PropertyDefinition $definition)
    {
        $this->document = $document;
        $this->definition = $definition;
    }

    /**
     * @param DocumentVisitor $visitor
     */
    public function acceptDocumentVisitor(DocumentVisitor $visitor)
    {
        $visitor->visitProperty($this->getDefinition());
    }

    /**
     * @param PropertyName $name
     *
     * @return bool
     */
    public function matchName(PropertyName $name): bool
    {
        return $name->matchName($this->definition->getName());
    }

    /**
     * @return PropertyDefinition
     */
    public function getDefinition(): PropertyDefinition
    {
        return $this->definition;
    }

    /**
     * @param DocumentDesigner $document
     * @param PropertyDefinition $definition
     *
     * @return DocumentProperty
     */
    public static function fromDefinition(DocumentDesigner $document, PropertyDefinition $definition): self
    {
        return new self($document, $definition);
    }
}
