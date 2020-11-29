<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\DomainEvent;

interface DocumentEvent extends DomainEvent
{
    public function documentId(): DocumentId;
}
