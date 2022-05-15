<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditableEvent;
use Star\Component\Document\Design\Domain\Model\DocumentId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

interface DocumentEvent extends AuditableEvent, CreatedFromPayload
{
    public function documentId(): DocumentId;
}
