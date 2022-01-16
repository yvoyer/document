<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query\DataTransfer;

use DateTimeInterface;
use Star\Component\Document\Design\Domain\Model\DocumentId;

final class ReadOnlyDocument
{
    private DocumentId $documentId;
    private string $documentName;
    private string $ownerId;
    private string $ownerName;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    public function __construct(
        DocumentId $documentId,
        string $documentName,
        string $ownerId,
        string $ownerName,
        DateTimeInterface $createdAt,
        DateTimeInterface $updatedAt
    ) {
        $this->documentId = $documentId;
        $this->documentName = $documentName;
        $this->ownerId = $ownerId;
        $this->ownerName = $ownerName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    final public function getDocumentId(): DocumentId
    {
        return $this->documentId;
    }

    final public function getDocumentName(string $locale): string
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

    final public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    final public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }
}
