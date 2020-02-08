<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

interface DocumentVisitor
{
    public function visitDocument(DocumentId $id): void;

    public function visitProperty(PropertyName $name, PropertyType $type): void;

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void;

    public function visitValueTransformer(
        PropertyName $propertyName,
        string $constraintName,
        TransformerIdentifier $identifier
    ): void;
}
