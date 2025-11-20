<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\DomainEvent\Messaging\Results\CollectionQuery;

final class GetAllRecordsOfDocument extends CollectionQuery
{
    /**
     * @var DocumentTypeId
     */
    private $documentId;

    /**
     * @param DocumentTypeId $documentId
     */
    public function __construct(DocumentTypeId $documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * @return DocumentTypeId
     */
    public function documentId(): DocumentTypeId
    {
        return $this->documentId;
    }

    public static function fromString(string $documentId): self
    {
        return new self(DocumentTypeId::fromString($documentId));
    }
}
