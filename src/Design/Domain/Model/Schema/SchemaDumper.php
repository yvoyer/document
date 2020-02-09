<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Schema;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\DocumentVisitor;
use Star\Component\Document\Design\Domain\Model\PropertyConstraint;
use Star\Component\Document\Design\Domain\Model\PropertyName;
use Star\Component\Document\Design\Domain\Model\PropertyType;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

final class SchemaDumper implements DocumentVisitor
{
    private $data = [];

    public function toArray(): array
    {
        return $this->data;
    }

    public function visitDocument(DocumentId $id): void
    {
        $this->data['id'] = $id->toString();
        $this->data['properties'] = [];
    }

    public function visitProperty(PropertyName $name, PropertyType $type): bool
    {
        $this->data['properties'][$name->toString()]['type'] = $type->toData()->toArray();
        $this->data['properties'][$name->toString()]['constraints'] = [];
        $this->data['properties'][$name->toString()]['transformers'] = [];

        return false;
    }

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void {
        $this->data['properties'][$propertyName->toString()]['constraints'][$constraintName] = $constraint->toData()->toArray();
    }

    public function visitValueTransformer(
        PropertyName $propertyName,
        string $constraintName,
        TransformerIdentifier $identifier
    ): void {
        $this->data['properties'][$propertyName->toString()]['transformers'][] = $identifier->toString();
    }
}
