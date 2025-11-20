<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;

interface DocumentDesigner
{
    /**
     * @return DocumentTypeId
     */
    public function getIdentity(): DocumentTypeId;

    public function getDefaultLocale(): string;

    /**
     * @param DocumentTypeVisitor $visitor
     */
    public function acceptDocumentVisitor(DocumentTypeVisitor $visitor): void;

    public function addProperty(
        PropertyCode $code,
        PropertyName $name,
        PropertyType $type,
        AuditDateTime $addedAt
    ): void;

    public function propertyExists(PropertyCode $code): bool;

    public function addPropertyConstraint(
        PropertyCode $code,
        string $constraintAlias,
        PropertyConstraint $constraint,
        AuditDateTime $addedAt
    ): void;

    public function addPropertyParameter(
        PropertyCode $code,
        string $parameterName,
        PropertyParameter $parameter,
        AuditDateTime $addedAt
    ): void;

    public function addDocumentConstraint(string $name, DocumentConstraint $constraint): void;

    public function removePropertyConstraint(PropertyCode $code, string $constraintName): void;
}
