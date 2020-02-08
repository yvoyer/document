<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;

final class DocumentSchema
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @var PropertyDefinition[]
     */
    private $properties = [];

    public function __construct(DocumentId $documentId)
    {
        $this->documentId = $documentId;
    }

    public function getIdentity(): DocumentId
    {
        return $this->documentId;
    }

    public function addProperty(string $name, PropertyType $type): void
    {
        $this->properties[$name] = new PropertyDefinition(PropertyName::fromString($name), $type);
    }

    public function addConstraint(string $property, string $constraintName, PropertyConstraint $constraint): void
    {
        $this->properties[$property] = $this->getDefinition($property)->addConstraint($constraintName, $constraint);
    }

    public function getDefinition(string $property): PropertyDefinition
    {
        if (!\array_key_exists($property, $this->properties)) {
            throw new ReferencePropertyNotFound(PropertyName::fromString($property));
        }

        return $this->properties[$property];
    }

    public function acceptDocumentVisitor(DocumentVisitor $visitor): void
    {
        $visitor->visitDocument($this->getIdentity());
        foreach ($this->properties as $property => $definition) {
            $definition->acceptDocumentVisitor($visitor);
        }
    }

    public function toString(): string
    {
        $data = [];
        $data['id'] = $this->documentId->toString();

        $properties = [];
        foreach ($this->properties as $name => $definition) {
            $properties[$name]['type'] = $definition->getType()->toData()->toJson();
        }
        $data['properties'] = $properties;

        return \json_encode($data);
    }

    public static function fromString(string $string): DocumentSchema
    {
        $data = \json_decode($string, true);
        $schema = new DocumentSchema(DocumentId::fromString($data['id']));
        foreach ($data['properties'] as $name => $property) {
            $typeData = TypeData::fromString($property['type']);
            $schema->addProperty($name, $typeData->createType());
        }

        return $schema;
    }
}
