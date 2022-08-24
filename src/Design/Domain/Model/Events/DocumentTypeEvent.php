<?php declare(strict_types=1);

namespace Star\Component\Document\Design\Domain\Model\Events;

use Star\Component\Document\Audit\Domain\Model\AuditableEvent;
use Star\Component\Document\Design\Domain\Model\DocumentTypeId;
use Star\Component\DomainEvent\Serialization\CreatedFromPayload;

interface DocumentTypeEvent extends AuditableEvent, CreatedFromPayload
{
    public function typeId(): DocumentTypeId;
}
