<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;

final class DocumentDesignerAggregate implements DocumentDesigner, ReadOnlyDocument
{
    /**
     * @var DocumentId
     */
    private $id;

    /**
     * @var DocumentState
     */
    private $state;

    /**
     * @var DocumentProperty[]
     */
    private $properties = [];

    /**
     * @param DocumentId $id
     */
    public function __construct(DocumentId $id)
    {
        $this->id = $id;
        $this->state = new DocumentState();
    }

    public function publish()
    {
        $this->state = $this->state->publish();
    }

    /**
     * @param PropertyName $name
     * @param ValueDefinition $definition
     */
    public function createProperty(PropertyName $name, ValueDefinition $definition)
    {
        $this->properties[] = new DocumentProperty($this, $name, $definition);
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->state->isPublished();
    }

    /**
     * @param DocumentVisitor $visitor
     */
    public function acceptDocumentVisitor(DocumentVisitor $visitor)
    {
        $visitor->visitDocument($this->id);
        foreach ($this->properties as $property) {
            $property->acceptDocumentVisitor($visitor);
        }
    }
}
