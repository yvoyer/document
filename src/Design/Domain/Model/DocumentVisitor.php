<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Common\Domain\Model\DocumentId;

interface DocumentVisitor
{
    public function visitDocument(DocumentId $id): void;

    /**
     * @param PropertyName $name
     * @param PropertyType $type
     * @return bool Whether to stop iteration after this visit
     */
    public function visitProperty(PropertyName $name, PropertyType $type): bool;

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void;

    public function visitParameter(PropertyName $propertyName, PropertyParameter $parameter): void;
}
