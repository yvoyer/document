<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Exception\ReferencePropertyNotFound;

final class DocumentDesignerAggregate implements DocumentDesigner
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

    public function getIdentity(): DocumentId
    {
        return $this->id;
    }

    public function publish()
    {
        $this->state = $this->state->publish();
    }

    /**
     * @param PropertyDefinition $definition
     */
    public function createProperty(PropertyDefinition $definition)
    {
        $this->properties[] = DocumentProperty::fromDefinition($this, $definition);
    }

    /**
     * @param PropertyName $name
     * @param PropertyAttribute $attribute
     */
    public function changePropertyAttribute(PropertyName $name, PropertyAttribute $attribute)
    {
        $attribute->updateDefinition($this->getPropertyDefinition($name->toString()));
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->state->isPublished();
    }

    /**
     * @param string $name
     *
     * @return PropertyDefinition
     */
    public function getPropertyDefinition(string $name): PropertyDefinition
    {
        $name = new PropertyName($name);
        foreach ($this->properties as $property) {
            if ($property->matchName($name)) {
                return $property->getDefinition();
            }
        }

        throw new ReferencePropertyNotFound($name);
    }

    /**
     * @param DocumentVisitor $visitor
     */
    public function acceptDocumentVisitor(DocumentVisitor $visitor)
    {
        $visitor->visitDocument($this->getIdentity());
        foreach ($this->properties as $property) {
            $property->acceptDocumentVisitor($visitor);
        }
    }
}
