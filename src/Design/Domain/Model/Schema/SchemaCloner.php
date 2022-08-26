<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\Document\Design\Domain\Model\DocumentTypeVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyCode;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class SchemaCloner implements DocumentTypeVisitor
{
    private DocumentSchema $schema;

    public function __construct(DocumentTypeId $id)
    {
        $this->schema = new DocumentSchema($id);
    }

    public function getClone(): DocumentSchema
    {
        return $this->schema;
    }

    public function visitDocumentType(DocumentTypeId $id): void
    {
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyCode $code, PropertyName $name, PropertyType $type): bool
    {
        $this->schema->addProperty($code, $name, $type);

        return false;
    }

    public function enterPropertyConstraints(PropertyCode $code): void
    {
    }

    public function visitPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->schema->addPropertyConstraint($code, $constraintName, $constraint);
    }

    public function enterPropertyParameters(PropertyCode $code): void
    {
    }

    public function visitPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->schema->addParameter($code, $parameterName, $parameter);
    }
}
