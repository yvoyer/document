<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

final class DocumentProperty
{
    /**
     * @var DocumentDesigner
     */
    private $document;

    /**
     * @var PropertyName
     */
    private $name;

    /**
     * @var ValueDefinition
     */
    private $definition;

    /**
     * @param DocumentDesigner $document
     * @param PropertyName $name
     * @param ValueDefinition $definition
     */
    public function __construct(DocumentDesigner $document, PropertyName $name, ValueDefinition $definition)
    {
        $this->document = $document;
        $this->name = $name;
        $this->definition = $definition;
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor)
    {
        $visitor->visitProperty($this->name, $this->definition);
    }
}
