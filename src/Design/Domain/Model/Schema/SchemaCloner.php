<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

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

    public function visitDocument(DocumentId $id): void
    {
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->schema->addProperty($name->toString(), $type);

        return false;
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->schema->addConstraint($propertyName->toString(), $constraintName, $constraint);
    }

    public function visitValueTransformer(
        PropertyName $propertyName,
        TransformerIdentifier $identifier
    ): void {
        $this->schema->addTransformer($propertyName->toString(), $identifier);
    }

    public function getClone(): DocumentSchema
    {
        return $this->schema;
    }
}
