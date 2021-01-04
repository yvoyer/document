<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\DomainEvent;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

interface DocumentEvent extends DomainEvent, CreatedFromPayload
{
    public function documentId(): DocumentId;
}
