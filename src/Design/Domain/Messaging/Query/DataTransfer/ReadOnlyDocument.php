<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer;

use Star\Component\Document\Audit\Domain\Model\AuditDateTime;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;

final class ReadOnlyDocument
{
    private DocumentTypeId $documentId;
    private string $documentName;
    private string $ownerId;
    private string $ownerName;
    private AuditDateTime $createdAt;
    private AuditDateTime $updatedAt;

    public function __construct(
        DocumentTypeId $documentId,
        string $documentName,
        string $ownerId,
        string $ownerName,
        AuditDateTime $createdAt,
        AuditDateTime $updatedAt
    ) {
        $this->documentId = $documentId;
        $this->documentName = $documentName;
        $this->ownerId = $ownerId;
        $this->ownerName = $ownerName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    final public function getDocumentId(): string
    {
        return $this->documentId->toString();
    }

    final public function getDocumentName(): string
    {
        return $this->documentName;
    }

    final public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    final public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    final public function getCreatedAt(): AuditDateTime
    {
        return $this->createdAt;
    }

    final public function getUpdatedAt(): AuditDateTime
    {
        return $this->updatedAt;
    }
}
