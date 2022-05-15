<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\DataEntry\Domain\Model\PropertyMetadata;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\Parameters\ParameterData;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Types\TypeData;
use function array_key_exists;
use function json_decode;
use function json_encode;
use function var_dump;

final class DocumentSchema implements SchemaMetadata
{
    private DocumentId $documentId;

    /**
     * @var PropertyDefinition[]
     */
    private array $properties = [];

    public function __construct(DocumentId $documentId)
    {
        $this->documentId = $documentId;
    }

    public function getIdentity(): DocumentId
    {
        return $this->documentId;
    }

    public function addProperty(PropertyName $property, PropertyType $type): void
    {
        $this->properties[$property->toString()] = new PropertyDefinition($property, $type);
    }

    public function hasProperty(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    public function getPropertyMetadata(string $property): PropertyMetadata
    {
        return $this->getPropertyDefinition($property);
    }

    public function removePropertyConstraint(string $property, string $constraint): void
    {
        $this->properties[$property] = $this->getPropertyDefinition($property)->removeConstraint($constraint);
    }

    public function addPropertyConstraint(
        string $property,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->properties[$property] = $this
            ->getPropertyDefinition($property)
            ->addConstraint($constraintName, $constraint);
    }

    public function addParameter(
        string $property,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->properties[$property] = $this
            ->getPropertyDefinition($property)
            ->addParameter($parameterName, $parameter);
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

        return (string) json_encode($dumper->toArray());
    }

    private function getPropertyDefinition(string $property): PropertyDefinition
    {
        if (!$this->hasProperty($property)) {
            throw new ReferencePropertyNotFound(PropertyName::fromString($property));
        }

        return $this->properties[$property];
    }

    public static function fromJsonString(string $string): DocumentSchema
    {
        $data = json_decode($string, true);
        $schema = new DocumentSchema(DocumentId::fromString($data[SchemaDumper::INDEX_ID]));
        foreach ($data[SchemaDumper::INDEX_PROPERTIES] as $propertyName => $propertyData) {
            $schema->addProperty(
                PropertyName::fromString($propertyName, 'LOCALE_TODO'),
                TypeData::fromArray($propertyData[SchemaDumper::INDEX_TYPE])->createType()
            );

            foreach ($propertyData[SchemaDumper::INDEX_CONSTRAINTS] as $constraintName => $constraintData) {
                $schema->addPropertyConstraint(
                    $propertyName,
                    $constraintName,
                    ConstraintData::fromArray($constraintData)->createPropertyConstraint()
                );
            }

            if (! array_key_exists(SchemaDumper::INDEX_PARAMETERS, $propertyData)) {
                $propertyData[SchemaDumper::INDEX_PARAMETERS] = [];
            }

            foreach ($propertyData[SchemaDumper::INDEX_PARAMETERS] as $parameterName => $parameterData) {
                $schema->addParameter(
                    $propertyName,
                    $parameterName,
                    ParameterData::fromArray($parameterData)->createParameter()
                );
            }
        }

        return $schema;
    }

    public static function baseSchema(DocumentId $documentId): self
    {
        return new self($documentId);
    }
}
