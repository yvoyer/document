<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;
use Star\Component\Document\DataEntry\Domain\Model\PropertyMetadata;
use Star\Component\Document\DataEntry\Domain\Model\SchemaMetadata;
use Star\Component\Document\Design\Domain\Model\Constraints\ConstraintData;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
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
    private DocumentTypeId $documentId;

    /**
     * @var PropertyDefinition[]
     */
    private array $properties = [];

    public function __construct(DocumentTypeId $documentId)
    {
        $this->documentId = $documentId;
    }

    public function getIdentity(): DocumentTypeId
    {
        return $this->documentId;
    }

    public function addProperty(PropertyCode $code, PropertyName $name, PropertyType $type): void
    {
        $this->properties[$code->toString()] = new PropertyDefinition($code, $name, $type);
    }

    public function hasProperty(PropertyCode $code): bool
    {
        return array_key_exists($code->toString(), $this->properties);
    }

    public function getPropertyMetadata(string $code): PropertyMetadata
    {
        return $this->getPropertyDefinition(PropertyCode::fromString($code));
    }

    public function removePropertyConstraint(PropertyCode $code, string $constraint): void
    {
        $this->properties[$code->toString()] = $this->getPropertyDefinition($code)->removeConstraint($constraint);
    }

    public function addPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->properties[$code->toString()] = $this
            ->getPropertyDefinition($code)
            ->addConstraint($constraintName, $constraint);
    }

    public function addParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->properties[$code->toString()] = $this
            ->getPropertyDefinition($code)
            ->addParameter($parameterName, $parameter);
    }

    public function acceptDocumentTypeVisitor(DocumentTypeVisitor $visitor): void
    {
        $visitor->visitDocumentType($this->getIdentity());
        foreach ($this->properties as $property => $definition) {
            $definition->acceptDocumentVisitor($visitor);
        }
    }

    public function clone(DocumentTypeId $id): DocumentSchema
    {
        $this->acceptDocumentTypeVisitor($cloner = new SchemaCloner($id));

        return $cloner->getClone();
    }

    public function toString(): string
    {
        $this->acceptDocumentTypeVisitor($dumper = new SchemaDumper());

        return (string) json_encode($dumper->toArray());
    }

    private function getPropertyDefinition(PropertyCode $property): PropertyDefinition
    {
        if (!$this->hasProperty($property)) {
            throw new ReferencePropertyNotFound($property);
        }

        return $this->properties[$property->toString()];
    }

    public static function fromJsonString(string $string): DocumentSchema
    {
        $data = json_decode($string, true);
        $schema = new DocumentSchema(
            DocumentTypeId::fromString($data[SchemaDumper::INDEX_ID])
        );
        foreach ($data[SchemaDumper::INDEX_PROPERTIES] as $propertyCode => $propertyData) {
            $schema->addProperty(
                $code = PropertyCode::fromString($propertyCode),
                PropertyName::fromSerializedString($propertyData[SchemaDumper::INDEX_PROPERTY_NAME]),
                TypeData::fromArray($propertyData[SchemaDumper::INDEX_PROPERTY_TYPE])->createType()
            );

            foreach ($propertyData[SchemaDumper::INDEX_CONSTRAINTS] as $constraintName => $constraintData) {
                $schema->addPropertyConstraint(
                    $code,
                    $constraintName,
                    ConstraintData::fromArray($constraintData)->createPropertyConstraint()
                );
            }

            if (! array_key_exists(SchemaDumper::INDEX_PARAMETERS, $propertyData)) {
                $propertyData[SchemaDumper::INDEX_PARAMETERS] = [];
            }

            foreach ($propertyData[SchemaDumper::INDEX_PARAMETERS] as $parameterName => $parameterData) {
                $schema->addParameter(
                    $code,
                    $parameterName,
                    ParameterData::fromArray($parameterData)->createParameter()
                );
            }
        }

        return $schema;
    }

    public static function baseSchema(DocumentTypeId $documentId): self
    {
        return new self($documentId);
    }
}
