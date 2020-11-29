<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;

final class DocumentCreated implements DocumentEvent
{
    /**
     * @var DocumentId
     */
    private $id;

    public function __construct(DocumentId $id)
    {
        $this->id = $id;
    }

    public function documentId(): DocumentId
    {
        return $this->id;
    }
}
