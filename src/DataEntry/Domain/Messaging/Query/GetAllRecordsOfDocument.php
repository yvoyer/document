<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Messaging\Results\CollectionQuery;

final class GetAllRecordsOfDocument extends CollectionQuery
{
    /**
     * @var DocumentId
     */
    private $documentId;

    /**
     * @param DocumentId $documentId
     */
    public function __construct(DocumentId $documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * @return DocumentId
     */
    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    public static function fromString(string $documentId): self
    {
        return new self(DocumentId::fromString($documentId));
    }
}
