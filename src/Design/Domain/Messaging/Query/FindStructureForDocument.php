<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Messaging\Query;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Messaging\Results\ObjectQuery;

final class FindStructureForDocument extends ObjectQuery
{
    /**
     * @var DocumentId
     */
    private $documentId;

    public function __construct(DocumentId $documentId)
    {
        $this->documentId = $documentId;
    }

    public function documentId(): DocumentId
    {
        return $this->documentId;
    }

    protected function getObjectType(): string
    {
        throw new \RuntimeException(__METHOD__ . ' not implemented yet.');
    }
}
