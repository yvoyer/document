<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Messaging\Query;

use Star\Component\Document\Common\Domain\Messaging\Query;
use Star\Component\Document\Common\Domain\Model\DocumentId;

final class GetAllRecordsOfDocument implements Query
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

    /**
     * @param string $documentId
     *
     * @return GetAllRecordsOfDocument
     */
    public static function fromString(string $documentId): self
    {
        return new self(new DocumentId($documentId));
    }
}
