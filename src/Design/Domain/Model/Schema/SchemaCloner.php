<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Design\Domain\Model\DocumentConstraint;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyParameter;
use Star\Component\Document\Design\Domain\Model\PropertyType;

final class SchemaCloner implements DocumentVisitor
{
    /**
     * @var DocumentSchema
     */
    private $schema;

    public function __construct(DocumentId $id)
    {
        $this->schema = new DocumentSchema($id);
    }

    public function getClone(): DocumentSchema
    {
        return $this->schema;
    }

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->schema->addProperty($name, $type);

        return false;
    }

    public function enterPropertyConstraints(PropertyName $propertyName): void
    {
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->schema->addPropertyConstraint($propertyName->toString(), $constraintName, $constraint);
    }

    public function enterPropertyParameters(PropertyName $propertyName): void
    {
    }

    public function visitPropertyParameter(
        PropertyName $propertyName,
        string $parameterName,
        PropertyParameter $parameter
    ): void {
        $this->schema->addParameter($propertyName->toString(), $parameterName, $parameter);
    }
}
