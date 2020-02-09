<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;
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

    public function removeConstraint(string $name, string $constraint): void
    {
        $this->properties[$name] = $this->getDefinition($name)->removeConstraint($constraint);
    }

    public function addConstraint(string $property, string $constraintName, PropertyConstraint $constraint): void
    {
        $this->properties[$property] = $this->getDefinition($property)->addConstraint($constraintName, $constraint);
    }

    public function addTransformer(string $property, TransformerIdentifier $identifier): void
    {
        $this->properties[$property] = $this->getDefinition($property)->addTransformer($identifier);
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
        $this->acceptDocumentVisitor($dumper = new SchemaDumper());

        return \json_encode($dumper->toArray());
    }

    public static function fromString(string $string): DocumentSchema
    {
        $data = \json_decode($string, true);
        $schema = new DocumentSchema(DocumentId::fromString($data['id']));
        foreach ($data['properties'] as $name => $property) {
            $schema->addProperty($name, TypeData::fromArray($property['type'])->createType());

            foreach ($property['constraints'] as $constraintName => $constraintData) {
                $schema->addConstraint(
                    $name, $constraintName, ConstraintData::fromArray($constraintData)->createConstraint()
                );
            }
        }

        return $schema;
    }
}
