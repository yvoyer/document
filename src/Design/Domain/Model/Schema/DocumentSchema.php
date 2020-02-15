<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
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

    public function addProperty(string $property, PropertyType $type): void
    {
        $this->properties[$property] = new PropertyDefinition(PropertyName::fromString($property), $type);
    }

    public function removeConstraint(string $property, string $constraint): void
    {
        $this->properties[$property] = $this->getDefinition($property)->removeConstraint($constraint);
    }

    public function addConstraint(string $property, PropertyConstraint $constraint): void
    {
        $this->properties[$property] = $this->getDefinition($property)->addConstraint($constraint);
    }

    public function addParameter(string $property, PropertyParameter $parameter): void
    {
        $this->properties[$property] = $this->getDefinition($property)->addParameter($parameter);
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

    public function clone(DocumentId $id): DocumentSchema
    {
        $this->acceptDocumentVisitor($cloner = new SchemaCloner($id));

        return $cloner->getClone();
    }

    public function toString(): string
    {
        $this->acceptDocumentVisitor($dumper = new SchemaDumper());

        return (string) \json_encode($dumper->toArray());
    }

    public static function fromString(string $string): DocumentSchema
    {
        $data = \json_decode($string, true);
        $schema = new DocumentSchema(DocumentId::fromString($data[SchemaDumper::INDEX_ID]));
        foreach ($data[SchemaDumper::INDEX_PROPERTIES] as $name => $property) {
            $schema->addProperty($name, TypeData::fromArray($property[SchemaDumper::INDEX_TYPE])->createType());

            foreach ($property[SchemaDumper::INDEX_CONSTRAINTS] as $constraintName => $constraintData) {
                $schema->addConstraint(
                    $name,
                    ConstraintData::fromArray($constraintData)->createConstraint()
                );
            }

            if (! \array_key_exists(SchemaDumper::INDEX_PARAMETERS, $property)) {
                $property[SchemaDumper::INDEX_PARAMETERS] = [];
            }

            foreach ($property[SchemaDumper::INDEX_PARAMETERS] as $parameterName => $parameter) {
                $schema->addParameter($parameterName, $parameter);
            }
        }

        return $schema;
    }
}
