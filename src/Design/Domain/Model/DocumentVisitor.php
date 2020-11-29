<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

interface DocumentVisitor
{
    public function visitDocument(DocumentId $id): void;

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void;

    /**
     * @param PropertyName $name
     * @param PropertyType $type
     * @return bool Whether to stop iteration after this visit
     */
    public function visitProperty(PropertyName $name, PropertyType $type): bool;

    /**
     * Executed before iteration on property constraints
     *
     * @param PropertyName $propertyName
     */
    public function enterPropertyConstraints(PropertyName $propertyName): void;

    public function visitPropertyConstraint(
        PropertyName $propertyName,
        string $constraintName,
        PropertyConstraint $constraint
    ): void;

    /**
     * Executed before iteration on property parameters
     *
     * @param PropertyName $propertyName
     */
    public function enterPropertyParameters(PropertyName $propertyName): void;

    public function visitPropertyParameter(
        PropertyName $propertyName,
        string $parameterName,
        PropertyParameter $parameter
    ): void;
}
