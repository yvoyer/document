<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Common\Domain\Model\DocumentId;
use Star\Component\DomainEvent\DomainEvent;

final class DocumentPublished implements DomainEvent
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
