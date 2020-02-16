<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Common\Domain\Model\DocumentId;
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

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->schema->addProperty($name->toString(), $type);

        return false;
    }

    public function enterConstraints(PropertyName $propertyName): void
    {
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->schema->addConstraint($propertyName->toString(), $constraint);
    }

    public function enterParameters(PropertyName $propertyName): void
    {
    }

    public function visitParameter(PropertyName $propertyName, PropertyParameter $parameter): void
    {
        $this->schema->addParameter($propertyName->toString(), $parameter);
    }
}
