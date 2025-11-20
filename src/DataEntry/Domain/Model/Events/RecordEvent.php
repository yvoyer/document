<?php declare(strict_types=1);

namespace Star\Component\Document\DataEntry\Domain\Model\Events;

use Star\Component\Document\DataEntry\Domain\Model\DocumentId;
use Star\Component\DomainEvent\DomainEvent;

interface RecordEvent extends DomainEvent
{
    public function recordId(): DocumentId;
}
