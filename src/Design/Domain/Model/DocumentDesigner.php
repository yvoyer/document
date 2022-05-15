<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model;

use DateTimeInterface;

interface DocumentDesigner
{
    /**
     * @return DocumentId
     */
    public function getIdentity(): DocumentId;

    /**
     * @param DocumentVisitor $visitor
     */
    public function acceptDocumentVisitor(DocumentVisitor $visitor): void;

    public function addProperty(
        PropertyName $name,
        PropertyType $type,
        DateTimeInterface $addedAt
    ): void;

    public function addPropertyConstraint(
        PropertyName $name,
        string $constraintName,
        PropertyConstraint $constraint,
        DateTimeInterface $addedAt
    ): void;

    public function addPropertyParameter(
        PropertyName $name,
        string $parameterName,
        PropertyParameter $parameter,
        DateTimeInterface $addedAt
    ): void;

    public function addDocumentConstraint(string $name, DocumentConstraint $constraint): void;

    public function removePropertyConstraint(PropertyName $name, string $constraintName): void;
}
