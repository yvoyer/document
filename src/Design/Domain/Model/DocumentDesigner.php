<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Design\Domain\Model\Transformation\TransformerIdentifier;

interface DocumentDesigner extends ReadOnlyDocument
{
    public function publish(): void;

    public function addProperty(
        PropertyName $name,
        PropertyType $type,
        PropertyConstraint $constraint
    ): void;

    public function addPropertyConstraint(
        PropertyName $name,
        string $constraintName,
        PropertyConstraint $constraint
    ): void;

    public function addPropertyTransformer(PropertyName $property, TransformerIdentifier $identifier): void;

    public function setDocumentConstraint(DocumentConstraint $constraint): void;

    public function removeConstraint(PropertyName $name, string $constraintName): void;
}
