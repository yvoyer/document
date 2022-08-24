<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\DataEntry\Domain\Model\PropertyCode;

interface DocumentTypeVisitor
{
    public function visitDocumentType(DocumentTypeId $id): void;

    public function visitDocumentConstraint(string $name, DocumentConstraint $constraint): void;

    /**
     * @param PropertyCode $code
     * @param PropertyName $name The default locale name of the property
     * @param PropertyType $type
     * @return bool Whether to stop iteration after this visit
     */
    public function visitProperty(PropertyCode $code, PropertyName $name, PropertyType $type): bool;

    /**
     * Executed before iteration on property constraints
     *
     * @param PropertyCode $code
     */
    public function enterPropertyConstraints(PropertyCode $code): void;

    public function visitPropertyConstraint(
        PropertyCode $code,
        string $constraintName,
        PropertyConstraint $constraint
    ): void;

    /**
     * Executed before iteration on property parameters
     *
     * @param PropertyCode $code
     */
    public function enterPropertyParameters(PropertyCode $code): void;

    public function visitPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter
    ): void;
}
